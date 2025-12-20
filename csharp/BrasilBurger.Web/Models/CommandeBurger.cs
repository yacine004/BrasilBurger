namespace BrasilBurger.Web.Models;

public class CommandeBurger
{
    public int Id { get; set; }
    public int IdCommande { get; set; }
    public int IdBurger { get; set; }
    public int Qte { get; set; }
    
    public Commande? Commande { get; set; }
    public Burger? Burger { get; set; }
}
