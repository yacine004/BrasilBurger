using BrasilBurger.Web.Models.DTOs;

namespace BrasilBurger.Web.Models.ViewModels
{
    public class BurgerListViewModel
    {
        public List<BurgerDto> Burgers { get; set; } = new();
        public List<ZoneDto> Zones { get; set; } = new();
        public string? SearchTerm { get; set; }
        public long? SelectedZoneId { get; set; }
        public int? MinPrice { get; set; }
        public int? MaxPrice { get; set; }
    }
}
