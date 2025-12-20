namespace BrasilBurger.Web.Models;

public class CommandeMenu
{
    public int Id { get; set; }
    public int IdCommande { get; set; }
    public int IdMenu { get; set; }
    public int Qte { get; set; }
    
    public Commande? Commande { get; set; }
    public Menu? Menu { get; set; }
}
