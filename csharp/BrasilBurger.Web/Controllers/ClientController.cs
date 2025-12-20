using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using BrasilBurger.Web.Data;
using BrasilBurger.Web.Models;
using System.Text;
using System.Security.Cryptography;

namespace BrasilBurger.Web.Controllers;

public class ClientController : Controller
{
    private readonly BrasilBurgerContext _context;

    public ClientController(BrasilBurgerContext context)
    {
        _context = context;
    }

    // Page de connexion
    public IActionResult Login()
    {
        return View();
    }

    // Traiter la connexion
    [HttpPost]
    public async Task<IActionResult> Login(string email, string motDePasse)
    {
        if (string.IsNullOrEmpty(email) || string.IsNullOrEmpty(motDePasse))
        {
            ModelState.AddModelError("", "Email et mot de passe requis");
            return View();
        }

        var client = await _context.Clients.FirstOrDefaultAsync(c => c.Email == email);
        if (client == null || string.IsNullOrEmpty(client.Password) || !VerifierMotDePasse(motDePasse, client.Password))
        {
            ModelState.AddModelError("", "Email ou mot de passe incorrect");
            return View();
        }

        // Stocker le client dans la session
        HttpContext.Session.SetInt32("ClientId", client.Id);
        HttpContext.Session.SetString("ClientNom", client.Nom);

        return RedirectToAction("Index", "Home");
    }

    // Page d'inscription
    public IActionResult Register()
    {
        return View();
    }

    // Traiter l'inscription
    [HttpPost]
    public async Task<IActionResult> Register(string nom, string prenom, string email, string telephone, string motDePasse, string confirmMotDePasse)
    {
        if (motDePasse != confirmMotDePasse)
        {
            ModelState.AddModelError("", "Les mots de passe ne correspondent pas");
            return View();
        }

        var clientExistant = await _context.Clients.FirstOrDefaultAsync(c => c.Email == email);
        if (clientExistant != null)
        {
            ModelState.AddModelError("", "Cet email est déjà utilisé");
            return View();
        }

        var client = new Client
        {
            Nom = nom,
            Prenom = prenom,
            Email = email,
            Telephone = telephone,
            Password = HashMotDePasse(motDePasse)
        };

        _context.Clients.Add(client);
        await _context.SaveChangesAsync();

        // Connexion automatique
        HttpContext.Session.SetInt32("ClientId", client.Id);
        HttpContext.Session.SetString("ClientNom", client.Nom);

        return RedirectToAction("Index", "Home");
    }

    // Déconnexion
    public IActionResult Logout()
    {
        HttpContext.Session.Clear();
        return RedirectToAction("Index", "Home");
    }

    // Profil du client
    public async Task<IActionResult> Profil()
    {
        var clientId = HttpContext.Session.GetInt32("ClientId");
        if (clientId == null)
            return RedirectToAction(nameof(Login));

        var client = await _context.Clients.FindAsync(clientId);
        if (client == null)
            return NotFound();

        return View(client);
    }

    // Mettre à jour le profil
    [HttpPost]
    public async Task<IActionResult> MettreAJourProfil(int id, string nom, string prenom, string telephone)
    {
        var clientId = HttpContext.Session.GetInt32("ClientId");
        if (clientId != id)
            return Unauthorized();

        var client = await _context.Clients.FindAsync(id);
        if (client == null)
            return NotFound();

        client.Nom = nom;
        client.Prenom = prenom;
        client.Telephone = telephone;

        _context.Clients.Update(client);
        await _context.SaveChangesAsync();

        return RedirectToAction(nameof(Profil));
    }

    // Historique des commandes
    public async Task<IActionResult> MesCommandes()
    {
        var clientId = HttpContext.Session.GetInt32("ClientId");
        if (clientId == null)
            return RedirectToAction(nameof(Login));

        var commandes = await _context.Commandes
            .Where(c => c.IdClient == clientId)
            .Include(c => c.Livraison)
            .Include(c => c.Paiement)
            .OrderByDescending(c => c.Date)
            .ToListAsync();

        return View(commandes);
    }

    // Helper: Hasher le mot de passe
    private string HashMotDePasse(string motDePasse)
    {
        using (var sha256 = SHA256.Create())
        {
            var hashedBytes = sha256.ComputeHash(Encoding.UTF8.GetBytes(motDePasse));
            return Convert.ToBase64String(hashedBytes);
        }
    }

    // Helper: Vérifier le mot de passe
    private bool VerifierMotDePasse(string motDePasse, string hash)
    {
        var hashMotDePasseFourni = HashMotDePasse(motDePasse);
        return hashMotDePasseFourni == hash;
    }
}
