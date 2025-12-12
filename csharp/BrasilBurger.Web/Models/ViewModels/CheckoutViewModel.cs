using BrasilBurger.Web.Models.DTOs;

namespace BrasilBurger.Web.Models.ViewModels
{
    public class CheckoutViewModel
    {
        public CartViewModel Cart { get; set; } = new();
        public List<ZoneDto> Zones { get; set; } = new();
        public ClientDto? Client { get; set; }
        public string DeliveryType { get; set; } = "A_DOMICILE"; // SUR_PLACE, A_DOMICILE
        public long SelectedZoneId { get; set; }
        public string DeliveryAddress { get; set; } = string.Empty;
        public string DeliveryPhone { get; set; } = string.Empty;
        public string Notes { get; set; } = string.Empty;
    }
}
