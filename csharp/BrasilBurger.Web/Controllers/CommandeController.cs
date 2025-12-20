using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using BrasilBurger.Web.Data;
using BrasilBurger.Web.Models;
using BrasilBurger.Web.Controllers;
using System.Text.Json;

namespace BrasilBurger.Web.Controllers;

public class CommandeController : Controller
{
    private readonly BrasilBurgerContext _context;
    private const string SessionKeyPanier = "Panier";

    public CommandeController(BrasilBurgerContext context)
    {
        _context = context;
    }

    // Obtenir le panier depuis la session
    private List<ItemPanier> GetPanier()
    {
        var session = HttpContext.Session.GetString(SessionKeyPanier);
        if (string.IsNullOrEmpty(session))
            return new List<ItemPanier>();

        return JsonSerializer.Deserialize<List<ItemPanier>>(session) ?? new List<ItemPanier>();
    }

    // Page de checkout
    public async Task<IActionResult> Checkout()
    {
        var panier = GetPanier();
        if (panier.Count == 0)
            return RedirectToAction("Index", "Produit", new { area = "" });

        var zones = await _context.Zones.Include(z => z.Quartiers).ToListAsync();
        var livreurs = await _context.Livreurs.ToListAsync();

        var viewModel = new CheckoutViewModel
        {
            Panier = panier,
            Zones = zones,
            Livreurs = livreurs
        };

        return View(viewModel);
    }

    // Valider et créer la commande
    [HttpPost]
    public async Task<IActionResult> ValiderCommande(int idClient, int idQuartier, int idLivreur, string adresseLivraison, string notes)
    {
        var panier = GetPanier();
        if (panier.Count == 0)
            return BadRequest("Le panier est vide");

        try
        {
            var client = await _context.Clients.FindAsync(idClient);
            if (client == null)
                return NotFound("Client non trouvé");

            var quartier = await _context.Quartiers.FindAsync(idQuartier);
            if (quartier == null)
                return NotFound("Quartier non trouvé");

            // Créer la commande
            var commande = new Commande
            {
                IdClient = idClient,
                Date = DateTime.UtcNow,
                Etat = "En attente",
                Mode = notes
            };

            _context.Commandes.Add(commande);
            await _context.SaveChangesAsync();

            // Ajouter les items de la commande
            foreach (var item in panier)
            {
                if (item.TypeProduit == "Burger")
                {
                    var burger = await _context.Burgers.FindAsync(item.IdProduit);
                    if (burger != null)
                    {
                        _context.CommandeBurgers.Add(new CommandeBurger
                        {
                            IdCommande = commande.Id,
                            IdBurger = item.IdProduit,
                            Qte = item.Quantite
                        });
                    }
                }
                else if (item.TypeProduit == "Menu")
                {
                    var menu = await _context.Menus.FindAsync(item.IdProduit);
                    if (menu != null)
                    {
                        _context.CommandeMenus.Add(new CommandeMenu
                        {
                            IdCommande = commande.Id,
                            IdMenu = item.IdProduit,
                            Qte = item.Quantite
                        });
                    }
                }
                else if (item.TypeProduit == "Complement")
                {
                    var complement = await _context.Complements.FindAsync(item.IdProduit);
                    if (complement != null)
                    {
                        _context.CommandeComplements.Add(new CommandeComplement
                        {
                            IdCommande = commande.Id,
                            IdComplement = item.IdProduit,
                            Qte = item.Quantite
                        });
                    }
                }
            }

            // Créer la livraison
            var livraison = new Livraison
            {
                IdCommande = commande.Id,
                IdLivreur = idLivreur
            };

            _context.Livraisons.Add(livraison);

            // Créer le paiement
            var montantTotal = await CalculerMontantTotal(panier);
            var paiement = new Paiement
            {
                IdCommande = commande.Id,
                Montant = montantTotal,
                Date = DateTime.UtcNow,
                Mode = "Carte Bancaire"
            };

            _context.Paiements.Add(paiement);
            await _context.SaveChangesAsync();

            // Vider le panier
            HttpContext.Session.Remove(SessionKeyPanier);

            // Rediriger vers la page de paiement
            return RedirectToAction("Paiement", new { id = commande.Id });
        }
        catch (Exception ex)
        {
            return BadRequest($"Erreur lors de la création de la commande: {ex.Message}");
        }
    }

    // Page de confirmation
    public async Task<IActionResult> Confirmation(int id)
    {
        var commande = await _context.Commandes
            .Include(c => c.Client)
            .Include(c => c.CommandeBurgers)
            .Include(c => c.CommandeMenus)
            .Include(c => c.CommandeComplements)
            .FirstOrDefaultAsync(c => c.Id == id);

        if (commande == null)
            return NotFound();

        return View(commande);
    }

    // Suivre une commande
    public async Task<IActionResult> Suivi(int id)
    {
        var commande = await _context.Commandes
            .Include(c => c.Paiement)
            .FirstOrDefaultAsync(c => c.Id == id);

        if (commande == null)
            return NotFound();

        return View(commande);
    }

    // Helper pour calculer le montant total
    // Page de paiement
    public async Task<IActionResult> Paiement(int id)
    {
        var commande = await _context.Commandes
            .Include(c => c.Client)
            .Include(c => c.CommandeBurgers)
            .Include(c => c.CommandeMenus)
            .Include(c => c.CommandeComplements)
            .FirstOrDefaultAsync(c => c.Id == id);

        if (commande == null)
            return NotFound("Commande non trouvée");

        return View(commande);
    }

    // Traiter le paiement
    [HttpPost]
    public async Task<IActionResult> ProcesserPaiement(int idCommande, string paymentMethod, string wavePhone, string orangePhone, bool termsAccepted)
    {
        try
        {
            if (!termsAccepted)
                return BadRequest("Vous devez accepter les conditions");

            if (string.IsNullOrEmpty(paymentMethod))
                return BadRequest("Veuillez sélectionner un mode de paiement");

            var commande = await _context.Commandes.FindAsync(idCommande);
            if (commande == null)
                return NotFound("Commande non trouvée");

            // Récupérer le paiement existant
            var paiement = await _context.Paiements.FirstOrDefaultAsync(p => p.IdCommande == idCommande);
            if (paiement != null)
            {
                // Mettre à jour le mode de paiement
                paiement.Mode = paymentMethod.ToUpper() == "WAVE" ? "Wave" : "Orange Money";
                paiement.Date = DateTime.UtcNow;
                _context.Paiements.Update(paiement);
            }

            // Mettre à jour le statut de la commande
            commande.Etat = "Confirmée";
            _context.Commandes.Update(commande);
            await _context.SaveChangesAsync();

            // Rediriger vers la page de confirmation
            return RedirectToAction(nameof(Confirmation), new { id = idCommande });
        }
        catch (Exception ex)
        {
            return BadRequest($"Erreur lors du paiement: {ex.Message}");
        }
    }

    private async Task<decimal> CalculerMontantTotal(List<ItemPanier> panier)
    {
        decimal total = 0;

        foreach (var item in panier)
        {
            if (item.TypeProduit == "Burger")
            {
                var burger = await _context.Burgers.FindAsync(item.IdProduit);
                if (burger != null)
                    total += burger.Prix * item.Quantite;
            }
            else if (item.TypeProduit == "Complement")
            {
                var complement = await _context.Complements.FindAsync(item.IdProduit);
                if (complement != null)
                    total += complement.Prix * item.Quantite;
            }
        }

        return total;
    }
}

// ViewModel pour le checkout
public class CheckoutViewModel
{
    public List<ItemPanier> Panier { get; set; } = new List<ItemPanier>();
    public List<Zone> Zones { get; set; } = new List<Zone>();
    public List<Livreur> Livreurs { get; set; } = new List<Livreur>();
}
