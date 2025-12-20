using BrasilBurger.Web.Data;
using BrasilBurger.Web.Services;
using Microsoft.AspNetCore.Mvc;

namespace BrasilBurger.Web.Controllers;

[ApiController]
[Route("api/[controller]")]
public class ImageController : ControllerBase
{
    private readonly CloudinaryService _cloudinaryService;
    private readonly BrasilBurgerContext _context;

    public ImageController(CloudinaryService cloudinaryService, BrasilBurgerContext context)
    {
        _cloudinaryService = cloudinaryService;
        _context = context;
    }

    [HttpPost("upload-burger-image/{burgerId}")]
    public async Task<IActionResult> UploadBurgerImage(int burgerId, IFormFile file)
    {
        try
        {
            if (file == null || file.Length == 0)
                return BadRequest(new { message = "Aucun fichier fourni" });

            // Vérifier que le burger existe
            var burger = await _context.Burgers.FindAsync(burgerId);
            if (burger == null)
                return NotFound(new { message = "Burger non trouvé" });

            // Upload sur Cloudinary
            var imageUrl = await _cloudinaryService.UploadImageAsync(file, "brasilburger/burgers");
            if (imageUrl == null)
                return BadRequest(new { message = "Erreur lors de l'upload" });

            // Mettre à jour en base de données
            burger.Image = imageUrl;
            _context.Burgers.Update(burger);
            await _context.SaveChangesAsync();

            return Ok(new { 
                message = "Image uploadée avec succès", 
                imageUrl = imageUrl,
                burgerId = burgerId 
            });
        }
        catch (Exception ex)
        {
            return StatusCode(500, new { message = $"Erreur serveur: {ex.Message}" });
        }
    }

    [HttpDelete("delete-burger-image/{burgerId}")]
    public async Task<IActionResult> DeleteBurgerImage(int burgerId)
    {
        try
        {
            var burger = await _context.Burgers.FindAsync(burgerId);
            if (burger == null)
                return NotFound(new { message = "Burger non trouvé" });

            if (!string.IsNullOrEmpty(burger.Image))
            {
                await _cloudinaryService.DeleteImageAsync(burger.Image);
            }

            burger.Image = null;
            _context.Burgers.Update(burger);
            await _context.SaveChangesAsync();

            return Ok(new { message = "Image supprimée avec succès" });
        }
        catch (Exception ex)
        {
            return StatusCode(500, new { message = $"Erreur serveur: {ex.Message}" });
        }
    }

    [HttpPost("upload-complement-image/{complementId}")]
    public async Task<IActionResult> UploadComplementImage(int complementId, IFormFile file)
    {
        try
        {
            if (file == null || file.Length == 0)
                return BadRequest(new { message = "Aucun fichier fourni" });

            var complement = await _context.Complements.FindAsync(complementId);
            if (complement == null)
                return NotFound(new { message = "Complément non trouvé" });

            var imageUrl = await _cloudinaryService.UploadImageAsync(file, "brasilburger/complements");
            if (imageUrl == null)
                return BadRequest(new { message = "Erreur lors de l'upload" });

            complement.Image = imageUrl;
            _context.Complements.Update(complement);
            await _context.SaveChangesAsync();

            return Ok(new { 
                message = "Image uploadée avec succès", 
                imageUrl = imageUrl,
                complementId = complementId 
            });
        }
        catch (Exception ex)
        {
            return StatusCode(500, new { message = $"Erreur serveur: {ex.Message}" });
        }
    }

    [HttpDelete("delete-complement-image/{complementId}")]
    public async Task<IActionResult> DeleteComplementImage(int complementId)
    {
        try
        {
            var complement = await _context.Complements.FindAsync(complementId);
            if (complement == null)
                return NotFound(new { message = "Complément non trouvé" });

            if (!string.IsNullOrEmpty(complement.Image))
            {
                await _cloudinaryService.DeleteImageAsync(complement.Image);
            }

            complement.Image = null;
            _context.Complements.Update(complement);
            await _context.SaveChangesAsync();

            return Ok(new { message = "Image supprimée avec succès" });
        }
        catch (Exception ex)
        {
            return StatusCode(500, new { message = $"Erreur serveur: {ex.Message}" });
        }
    }

    [HttpPost("upload-menu-image/{menuId}")]
    public async Task<IActionResult> UploadMenuImage(int menuId, IFormFile file)
    {
        try
        {
            if (file == null || file.Length == 0)
                return BadRequest(new { message = "Aucun fichier fourni" });

            var menu = await _context.Menus.FindAsync(menuId);
            if (menu == null)
                return NotFound(new { message = "Menu non trouvé" });

            var imageUrl = await _cloudinaryService.UploadImageAsync(file, "brasilburger/menus");
            if (imageUrl == null)
                return BadRequest(new { message = "Erreur lors de l'upload" });

            menu.Image = imageUrl;
            _context.Menus.Update(menu);
            await _context.SaveChangesAsync();

            return Ok(new { 
                message = "Image uploadée avec succès", 
                imageUrl = imageUrl,
                menuId = menuId 
            });
        }
        catch (Exception ex)
        {
            return StatusCode(500, new { message = $"Erreur serveur: {ex.Message}" });
        }
    }

    [HttpDelete("delete-menu-image/{menuId}")]
    public async Task<IActionResult> DeleteMenuImage(int menuId)
    {
        try
        {
            var menu = await _context.Menus.FindAsync(menuId);
            if (menu == null)
                return NotFound(new { message = "Menu non trouvé" });

            if (!string.IsNullOrEmpty(menu.Image))
            {
                await _cloudinaryService.DeleteImageAsync(menu.Image);
            }

            menu.Image = null;
            _context.Menus.Update(menu);
            await _context.SaveChangesAsync();

            return Ok(new { message = "Image supprimée avec succès" });
        }
        catch (Exception ex)
        {
            return StatusCode(500, new { message = $"Erreur serveur: {ex.Message}" });
        }
    }
}
