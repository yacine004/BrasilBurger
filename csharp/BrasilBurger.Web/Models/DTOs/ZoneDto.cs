namespace BrasilBurger.Web.Models.DTOs
{
    public class ZoneDto
    {
        public long Id { get; set; }
        public string Nom { get; set; } = string.Empty;
        public string Description { get; set; } = string.Empty;
        public bool Disponible { get; set; }
        public DateTime CreatedAt { get; set; }
        public DateTime UpdatedAt { get; set; }
    }
}
