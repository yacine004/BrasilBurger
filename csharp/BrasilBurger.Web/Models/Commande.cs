namespace BrasilBurger.Web.Models;

public class Commande
{
    public int Id { get; set; }
    public DateTime Date { get; set; } = DateTime.UtcNow;
    public string? Etat { get; set; }
    public string? Mode { get; set; }
    public decimal Montant { get; set; }
    public int IdClient { get; set; }
    
    public Client? Client { get; set; }
    public ICollection<CommandeBurger> CommandeBurgers { get; set; } = new List<CommandeBurger>();
    public ICollection<CommandeMenu> CommandeMenus { get; set; } = new List<CommandeMenu>();
    public ICollection<CommandeComplement> CommandeComplements { get; set; } = new List<CommandeComplement>();
    public Livraison? Livraison { get; set; }
    public Paiement? Paiement { get; set; }
}
