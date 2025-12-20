namespace BrasilBurger.Web.Models;

public class Quartier
{
    public int Id { get; set; }
    public string Nom { get; set; } = string.Empty;
    public int IdZone { get; set; }
    
    public Zone? Zone { get; set; }
}
