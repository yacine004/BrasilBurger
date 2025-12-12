namespace BrasilBurger.Web.Models.ViewModels
{
    public class PaymentViewModel
    {
        public long OrderId { get; set; }
        public decimal Amount { get; set; }
        public string PaymentMethod { get; set; } = "CARTE"; // CARTE, ESPECES, MOBILE
        public string CardNumber { get; set; } = string.Empty;
        public string CardExpiry { get; set; } = string.Empty;
        public string CardCvv { get; set; } = string.Empty;
    }
}
