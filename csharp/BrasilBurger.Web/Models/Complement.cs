namespace BrasilBurger.Web.Models;

public class Complement
{
    public int Id { get; set; }
    public string Nom { get; set; } = string.Empty;
    public decimal Prix { get; set; }
    public string? Image { get; set; }
    public bool Etat { get; set; } = true;
    
    public ICollection<CommandeComplement> CommandeComplements { get; set; } = new List<CommandeComplement>();
}
