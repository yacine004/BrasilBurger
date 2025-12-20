using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using BrasilBurger.Web.Data;
using BrasilBurger.Web.Models;
using System.Text.Json;

namespace BrasilBurger.Web.Controllers;

public class PanierController : Controller
{
    private readonly BrasilBurgerContext _context;
    private const string SessionKeyPanier = "Panier";

    public PanierController(BrasilBurgerContext context)
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

    // Sauvegarder le panier dans la session
    private void SauvegarderPanier(List<ItemPanier> panier)
    {
        var json = JsonSerializer.Serialize(panier);
        HttpContext.Session.SetString(SessionKeyPanier, json);
    }

    // Afficher le panier
    public IActionResult Index()
    {
        var panier = GetPanier();
        return View(panier);
    }

    // Ajouter un burger au panier
    [HttpPost]
    public async Task<IActionResult> AjouterBurger(int id, int quantite = 1, string? modelivraison = null, int[]? complements = null)
    {
        if (quantite <= 0)
            return BadRequest("La quantité doit être positive");

        var burger = await _context.Burgers.FindAsync(id);
        if (burger == null)
            return NotFound("Burger non trouvé");

        var panier = GetPanier();
        
        // Calculer le prix total avec compléments
        decimal prixUnitaire = burger.Prix;
        if (complements != null && complements.Length > 0)
        {
            var complementsData = await _context.Complements
                .Where(c => complements.Contains(c.Id))
                .ToListAsync();
            prixUnitaire += complementsData.Sum(c => c.Prix);
        }

        var itemExistant = panier.FirstOrDefault(p => p.TypeProduit == "Burger" && p.IdProduit == id);

        if (itemExistant != null)
        {
            itemExistant.Quantite += quantite;
        }
        else
        {
            panier.Add(new ItemPanier
            {
                IdProduit = id,
                Nom = burger.Nom,
                TypeProduit = "Burger",
                Quantite = quantite,
                PrixUnitaire = prixUnitaire
            });
        }

        SauvegarderPanier(panier);
        return RedirectToAction("Index");
    }

    // Ajouter un menu au panier
    [HttpPost]
    public IActionResult AjouterMenu(int id, int quantite = 1)
    {
        if (quantite <= 0)
            return BadRequest("La quantité doit être positive");

        var panier = GetPanier();
        var itemExistant = panier.FirstOrDefault(p => p.TypeProduit == "Menu" && p.IdProduit == id);

        if (itemExistant != null)
        {
            itemExistant.Quantite += quantite;
        }
        else
        {
            panier.Add(new ItemPanier
            {
                IdProduit = id,
                TypeProduit = "Menu",
                Quantite = quantite
            });
        }

        SauvegarderPanier(panier);
        return Ok(new { message = "Menu ajouté au panier", totalItems = panier.Sum(p => p.Quantite) });
    }

    // Ajouter un complément au panier
    [HttpPost]
    public IActionResult AjouterComplement(int id, int quantite = 1)
    {
        if (quantite <= 0)
            return BadRequest("La quantité doit être positive");

        var panier = GetPanier();
        var itemExistant = panier.FirstOrDefault(p => p.TypeProduit == "Complement" && p.IdProduit == id);

        if (itemExistant != null)
        {
            itemExistant.Quantite += quantite;
        }
        else
        {
            panier.Add(new ItemPanier
            {
                IdProduit = id,
                TypeProduit = "Complement",
                Quantite = quantite
            });
        }

        SauvegarderPanier(panier);
        return Ok(new { message = "Complément ajouté au panier", totalItems = panier.Sum(p => p.Quantite) });
    }

    // Supprimer un item du panier
    [HttpPost]
    public IActionResult Supprimer(int id, string type)
    {
        var panier = GetPanier();
        var item = panier.FirstOrDefault(p => p.IdProduit == id && p.TypeProduit == type);

        if (item != null)
        {
            panier.Remove(item);
            SauvegarderPanier(panier);
        }

        return RedirectToAction(nameof(Index));
    }

    // Mettre à jour la quantité
    [HttpPost]
    public IActionResult MettreAJourQuantite(int id, string type, int quantite)
    {
        if (quantite <= 0)
        {
            return Supprimer(id, type);
        }

        var panier = GetPanier();
        var item = panier.FirstOrDefault(p => p.IdProduit == id && p.TypeProduit == type);

        if (item != null)
        {
            item.Quantite = quantite;
            SauvegarderPanier(panier);
        }

        return RedirectToAction(nameof(Index));
    }

    // Vider le panier
    [HttpPost]
    public IActionResult Vider()
    {
        HttpContext.Session.Remove(SessionKeyPanier);
        return RedirectToAction(nameof(Index));
    }

    // Obtenir le nombre d'items du panier (pour le header)
    [HttpGet]
    public IActionResult NombreItems()
    {
        var panier = GetPanier();
        return Json(new { count = panier.Sum(p => p.Quantite) });
    }
}

// Classe pour stocker les items du panier
public class ItemPanier
{
    public int IdProduit { get; set; }
    public string TypeProduit { get; set; } = string.Empty; // "Burger", "Menu", "Complement"
    public int Quantite { get; set; }
    public decimal PrixUnitaire { get; set; }
    public string Nom { get; set; } = string.Empty;
    public decimal? PrixTotal => PrixUnitaire * Quantite;
}
