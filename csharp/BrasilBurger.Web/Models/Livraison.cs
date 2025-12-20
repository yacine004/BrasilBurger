namespace BrasilBurger.Web.Models;

public class Livraison
{
    public int Id { get; set; }
    public int IdCommande { get; set; }
    public int IdLivreur { get; set; }
    
    public Commande? Commande { get; set; }
    public Livreur? Livreur { get; set; }
}
