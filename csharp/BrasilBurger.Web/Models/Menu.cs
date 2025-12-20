namespace BrasilBurger.Web.Models;

public class Menu
{
    public int Id { get; set; }
    public string Nom { get; set; } = string.Empty;
    public string? Image { get; set; }
    public decimal Prix { get; set; }
    
    public ICollection<MenuBurger> MenuBurgers { get; set; } = new List<MenuBurger>();
    public ICollection<CommandeMenu> CommandeMenus { get; set; } = new List<CommandeMenu>();
}
