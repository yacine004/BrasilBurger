using BrasilBurger.Web.Data;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;

namespace BrasilBurger.Web.Controllers;

[ApiController]
[Route("api/[controller]")]
public class ComplementController : ControllerBase
{
    private readonly BrasilBurgerContext _context;

    public ComplementController(BrasilBurgerContext context)
    {
        _context = context;
    }

    [HttpGet("list")]
    public async Task<IActionResult> List()
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

            return Ok(complements);
        }
        catch (Exception ex)
        {
            return StatusCode(500, new { message = $"Erreur serveur: {ex.Message}" });
        }
    }
}
