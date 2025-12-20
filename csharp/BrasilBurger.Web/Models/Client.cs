namespace BrasilBurger.Web.Models;

public class Client
{
    public int Id { get; set; }
    public string Nom { get; set; } = string.Empty;
    public string Prenom { get; set; } = string.Empty;
    public string? Telephone { get; set; }
    public string? Email { get; set; }
    public string? Password { get; set; }
    
    public ICollection<Commande> Commandes { get; set; } = new List<Commande>();
}
