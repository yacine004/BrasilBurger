namespace BrasilBurger.Web.Models.DTOs
{
    public class ComplementDto
    {
        public long Id { get; set; }
        public string Nom { get; set; } = string.Empty;
        public string Description { get; set; } = string.Empty;
        public decimal Prix { get; set; }
        public string Type { get; set; } = string.Empty; // BOISSON, SAUCE, ACCOMPAGNEMENT
        public bool Disponible { get; set; }
        public DateTime CreatedAt { get; set; }
        public DateTime UpdatedAt { get; set; }
    }
}
