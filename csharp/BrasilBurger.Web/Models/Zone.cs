namespace BrasilBurger.Web.Models;

public class Zone
{
    public int Id { get; set; }
    public string Nom { get; set; } = string.Empty;
    public decimal PrixLivraison { get; set; }
    
    public ICollection<Quartier> Quartiers { get; set; } = new List<Quartier>();
}
