namespace BrasilBurger.Web.Models;

public class Paiement
{
    public int Id { get; set; }
    public DateTime Date { get; set; } = DateTime.UtcNow;
    public decimal Montant { get; set; }
    public string? Mode { get; set; }
    public int IdCommande { get; set; }
    
    public Commande? Commande { get; set; }
}
