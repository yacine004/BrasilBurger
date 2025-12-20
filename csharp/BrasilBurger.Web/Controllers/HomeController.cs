using System.Diagnostics;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using BrasilBurger.Web.Models;
using BrasilBurger.Web.Data;
using BrasilBurger.Web.Services;

namespace BrasilBurger.Web.Controllers;

public class HomeController : Controller
{
    private readonly BrasilBurgerContext _context;

    public HomeController(BrasilBurgerContext context)
    {
        _context = context;
    }

    public async Task<IActionResult> Index()
    {
        var homeViewModel = new HomeViewModel
        {
            BurgersRecents = await _context.Burgers.Take(6).ToListAsync(),
            MenusRecents = await _context.Menus.Take(3).ToListAsync(),
            MenusPopulaires = await _context.Menus.Take(3).ToListAsync(),
            ComplementsPopulaires = await _context.Complements.Take(6).ToListAsync()
        };
        return View(homeViewModel);
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
