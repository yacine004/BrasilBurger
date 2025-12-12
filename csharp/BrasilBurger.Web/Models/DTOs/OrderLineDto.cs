namespace BrasilBurger.Web.Models.DTOs
{
    public class OrderLineDto
    {
        public long Id { get; set; }
        public long OrderId { get; set; }
        public long BurgerId { get; set; }
        public int Quantite { get; set; }
        public decimal PrixUnitaire { get; set; }
        public decimal SousTotal { get; set; }
        public List<long> ComplementIds { get; set; } = new();
        public DateTime CreatedAt { get; set; }
        public DateTime UpdatedAt { get; set; }
    }
}
