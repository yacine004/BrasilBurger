using BrasilBurger.Web.Models.DTOs;

namespace BrasilBurger.Web.Models.ViewModels
{
    public class OrderViewModel
    {
        public OrderDto? Order { get; set; }
        public ClientDto? Client { get; set; }
        public PaymentDto? Payment { get; set; }
        public List<BurgerDto> Burgers { get; set; } = new();
        public List<ComplementDto> Complements { get; set; } = new();
    }
}
