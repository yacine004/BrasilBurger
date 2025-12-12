namespace BrasilBurger.Web.Models.DTOs
{
    public class BurgerDto
    {
        public long Id { get; set; }
        public string Nom { get; set; } = string.Empty;
        public string Description { get; set; } = string.Empty;
        public decimal Prix { get; set; }
        public string Image { get; set; } = string.Empty;
        public bool Disponible { get; set; }
        public int Stock { get; set; }
        public DateTime CreatedAt { get; set; }
        public DateTime UpdatedAt { get; set; }
    }
}
