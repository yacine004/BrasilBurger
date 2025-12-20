using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using BrasilBurger.Web.Data;
using BrasilBurger.Web.Models;
using BrasilBurger.Web.Services;

namespace BrasilBurger.Web.Controllers;

public class ProduitController : Controller
{
    private readonly BrasilBurgerContext _context;

    public ProduitController(BrasilBurgerContext context)
    {
        _context = context;
    }

    // Afficher tous les burgers
    public async Task<IActionResult> Burgers()
    {
        var burgers = await _context.Burgers.ToListAsync();
        return View(burgers);
    }

    // Afficher tous les menus
    public async Task<IActionResult> Menus()
    {
        var menus = await _context.Menus
            .Include(m => m.MenuBurgers)
            .ToListAsync();
        return View(menus);
    }

    // Afficher tous les compléments
    public async Task<IActionResult> Complements()
    {
        var complements = await _context.Complements.ToListAsync();
        return View(complements);
    }

    // Détail d'un burger
    public async Task<IActionResult> DetailBurger(int id)
    {
        var burger = await _context.Burgers.FindAsync(id);
        if (burger == null)
            return NotFound();
        return View(burger);
    }

    // Détail d'un menu
    public async Task<IActionResult> DetailMenu(int id)
    {
        var menu = await _context.Menus
            .Include(m => m.MenuBurgers)
            .ThenInclude(mb => mb.Burger)
            .FirstOrDefaultAsync(m => m.Id == id);
        if (menu == null)
            return NotFound();
        return View(menu);
    }

    // Gestion des images des burgers
    public async Task<IActionResult> GestionImages()
    {
        var burgers = await _context.Burgers.ToListAsync();
        return View(burgers);
    }

    // Gestion des images des menus
    public async Task<IActionResult> GestionMenuImages()
    {
        var menus = await _context.Menus.ToListAsync();
        return View(menus);
    }

    // Gestion complète des menus (avec burgers et images)
    public async Task<IActionResult> GestionMenus()
    {
        var menus = await _context.Menus
            .Include(m => m.MenuBurgers)
            .ThenInclude(mb => mb.Burger)
            .ToListAsync();
        return View(menus);
    }

    // Gestion des images des compléments
    public async Task<IActionResult> GestionComplements()
    {
        var complements = await _context.Complements.ToListAsync();
        return View(complements);
    }

    // API JSON pour charger les compléments
    [HttpGet]
    public async Task<IActionResult> ComplementsJson()
    {
        try
        {
            var complements = await _context.Complements
                .Where(c => c.Etat)
                .Select(c => new
                {
                    c.Id,
                    c.Nom,
                    c.Prix,
                    c.Image
                })
                .ToListAsync();

            return Json(complements);
        }
        catch (Exception ex)
        {
            return Json(new { error = ex.Message });
        }
    }

    // Upload image du menu
    [HttpPost]
    public async Task<IActionResult> UploadMenuImage(int menuId, IFormFile image)
    {
        try
        {
            if (image == null || image.Length == 0)
                return Json(new { success = false, message = "Aucun fichier fourni" });

            var menu = await _context.Menus.FindAsync(menuId);
            if (menu == null)
                return Json(new { success = false, message = "Menu non trouvé" });

            // Upload vers Cloudinary
            var cloudinaryService = HttpContext.RequestServices.GetRequiredService<CloudinaryService>();
            var publicId = $"brasilburger/menus/menu_{menuId}_{DateTime.Now.Ticks}";
            var imageUrl = await cloudinaryService.UploadImageAsync(image, publicId);

            if (string.IsNullOrEmpty(imageUrl))
                return Json(new { success = false, message = "Erreur lors de l'upload" });

            menu.Image = imageUrl;
            _context.Menus.Update(menu);
            await _context.SaveChangesAsync();

            return Json(new { success = true, imageUrl });
        }
        catch (Exception ex)
        {
            return Json(new { success = false, message = ex.Message });
        }
    }

    // Supprimer image du menu
    [HttpDelete]
    public async Task<IActionResult> DeleteMenuImage(int menuId)
    {
        try
        {
            var menu = await _context.Menus.FindAsync(menuId);
            if (menu == null)
                return Json(new { success = false, message = "Menu non trouvé" });

            if (!string.IsNullOrEmpty(menu.Image))
            {
                var cloudinaryService = HttpContext.RequestServices.GetRequiredService<CloudinaryService>();
                await cloudinaryService.DeleteImageAsync(menu.Image);
            }

            menu.Image = null;
            _context.Menus.Update(menu);
            await _context.SaveChangesAsync();

            return Json(new { success = true });
        }
        catch (Exception ex)
        {
            return Json(new { success = false, message = ex.Message });
        }
    }
}
