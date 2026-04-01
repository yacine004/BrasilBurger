using System.Diagnostics;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using BrasilBurger.Web.Models;
using BrasilBurger.Web.Data;
using BrasilBurger.Web.Services;
using Microsoft.Extensions.Logging;

namespace BrasilBurger.Web.Controllers;

public class HomeController : Controller
{
    private readonly BrasilBurgerContext _context;
    private readonly ILogger<HomeController> _logger;

    public HomeController(BrasilBurgerContext context, ILogger<HomeController> logger)
    {
        _context = context;
        _logger = logger;
    }

    public async Task<IActionResult> Index()
    {
        try
        {
            _logger.LogInformation("Tentative d'accès à la page d'accueil");
            var homeViewModel = new HomeViewModel
            {
                BurgersRecents = await _context.Burgers.OrderByDescending(b => b.Id).Take(6).ToListAsync(),
                MenusRecents = await _context.Menus.OrderByDescending(m => m.Id).Take(3).ToListAsync(),
                MenusPopulaires = await _context.Menus.OrderByDescending(m => m.Id).Take(3).ToListAsync(),
                ComplementsPopulaires = await _context.Complements.OrderByDescending(c => c.Id).Take(6).ToListAsync()
            };
            _logger.LogInformation($"Burgers récents: {homeViewModel.BurgersRecents.Count}, Menus récents: {homeViewModel.MenusRecents.Count}");
            return View(homeViewModel);
        }
        catch (Exception ex)
        {
            _logger.LogError(ex, "Erreur lors de la récupération des données de la page d'accueil");
            throw;
        }
    }

    public IActionResult Privacy()
    {
        return View();
    }

    // Gestion de l'image hero
    public IActionResult GestionHero()
    {
        return View();
    }

    // Récupérer l'URL de l'image hero
    [HttpGet]
    public async Task<IActionResult> GetHeroImage()
    {
        try
        {
            // Charger l'URL du hero depuis la base de données
            var config = await _context.Configurations
                .FirstOrDefaultAsync(c => c.Cle == "HeroImage");

            var heroUrl = config?.Valeur ?? "";
            return Json(new { imageUrl = heroUrl });
        }
        catch (Exception ex)
        {
            return Json(new { error = ex.Message });
        }
    }

    // Upload l'image hero
    [HttpPost]
    public async Task<IActionResult> UploadHeroImage(IFormFile image)
    {
        try
        {
            if (image == null || image.Length == 0)
                return Json(new { success = false, message = "Aucun fichier fourni" });

            // Upload vers Cloudinary
            var cloudinaryService = HttpContext.RequestServices.GetRequiredService<CloudinaryService>();
            var publicId = $"brasilburger/hero/hero_{DateTime.Now.Ticks}";
            var imageUrl = await cloudinaryService.UploadImageAsync(image, publicId);

            if (string.IsNullOrEmpty(imageUrl))
                return Json(new { success = false, message = "Erreur lors de l'upload" });

            // Sauvegarder l'URL en base de données
            var config = await _context.Configurations
                .FirstOrDefaultAsync(c => c.Cle == "HeroImage");

            if (config == null)
            {
                config = new Configuration { Cle = "HeroImage", Valeur = imageUrl };
                _context.Configurations.Add(config);
            }
            else
            {
                config.Valeur = imageUrl;
                _context.Configurations.Update(config);
            }

            await _context.SaveChangesAsync();

            return Json(new { success = true, imageUrl });
        }
        catch (Exception ex)
        {
            return Json(new { success = false, message = ex.Message });
        }
    }

    // Supprimer l'image hero
    [HttpDelete]
    public async Task<IActionResult> DeleteHeroImage()
    {
        try
        {
            var config = await _context.Configurations
                .FirstOrDefaultAsync(c => c.Cle == "HeroImage");

            if (config != null && !string.IsNullOrEmpty(config.Valeur))
            {
                var cloudinaryService = HttpContext.RequestServices.GetRequiredService<CloudinaryService>();
                await cloudinaryService.DeleteImageAsync(config.Valeur);

                // Effacer l'URL de la base de données
                config.Valeur = null;
                _context.Configurations.Update(config);
                await _context.SaveChangesAsync();
            }

            return Json(new { success = true });
        }
        catch (Exception ex)
        {
            return Json(new { success = false, message = ex.Message });
        }
    }

    [ResponseCache(Duration = 0, Location = ResponseCacheLocation.None, NoStore = true)]
    public IActionResult Error()
    {
        return View(new ErrorViewModel { RequestId = Activity.Current?.Id ?? HttpContext.TraceIdentifier });
    }
}
