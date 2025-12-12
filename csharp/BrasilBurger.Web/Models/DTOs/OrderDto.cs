namespace BrasilBurger.Web.Models.DTOs
{
    public class OrderDto
    {
        public long Id { get; set; }
        public long ClientId { get; set; }
        public long ZoneId { get; set; }
        public List<OrderLineDto> Lignes { get; set; } = new();
        public decimal MontantTotal { get; set; }
        public string Statut { get; set; } = string.Empty; // EN_ATTENTE, CONFIRMEE, EN_PREPARATION, PRETE, LIVREE
        public string Etat { get; set; } = string.Empty; // ACTIF, ANNULEE
        public string TypeLivraison { get; set; } = string.Empty; // SUR_PLACE, A_DOMICILE
        public string Adresse { get; set; } = string.Empty;
        public string Telephone { get; set; } = string.Empty;
        public string Notes { get; set; } = string.Empty;
        public DateTime CreatedAt { get; set; }
        public DateTime UpdatedAt { get; set; }
    }
}
