namespace BrasilBurger.Web.Models.DTOs
{
    public class PaymentDto
    {
        public long Id { get; set; }
        public long OrderId { get; set; }
        public decimal Montant { get; set; }
        public string Methode { get; set; } = string.Empty; // CARTE, ESPECES, MOBILE
        public string Statut { get; set; } = string.Empty; // EN_ATTENTE, CONFIRMEE, ECHOUEE, REMBOURSEE
        public string Reference { get; set; } = string.Empty;
        public DateTime CreatedAt { get; set; }
        public DateTime UpdatedAt { get; set; }
    }
}
