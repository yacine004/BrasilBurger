namespace BrasilBurger.Web.Models;

public class MenuBurger
{
    public int Id { get; set; }
    public int IdMenu { get; set; }
    public int IdBurger { get; set; }
    
    public Menu? Menu { get; set; }
    public Burger? Burger { get; set; }
}
