namespace BrasilBurger.Web.Models;

public class CommandeComplement
{
    public int Id { get; set; }
    public int IdCommande { get; set; }
    public int IdComplement { get; set; }
    public int Qte { get; set; }
    
    public Commande? Commande { get; set; }
    public Complement? Complement { get; set; }
}
