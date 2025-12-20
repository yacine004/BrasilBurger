namespace BrasilBurger.Web.Models;

public class Burger
{
    public int Id { get; set; }
    public string Nom { get; set; } = string.Empty;
    public decimal Prix { get; set; }
    public string? Image { get; set; }
    public bool Etat { get; set; } = true;
    
    public ICollection<MenuBurger> MenuBurgers { get; set; } = new List<MenuBurger>();
    public ICollection<CommandeBurger> CommandeBurgers { get; set; } = new List<CommandeBurger>();
}
