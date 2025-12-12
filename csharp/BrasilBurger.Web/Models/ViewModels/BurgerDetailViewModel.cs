using BrasilBurger.Web.Models.DTOs;

namespace BrasilBurger.Web.Models.ViewModels
{
    public class BurgerDetailViewModel
    {
        public BurgerDto? Burger { get; set; }
        public List<ComplementDto> Complements { get; set; } = new();
        public List<MenuDto> Menus { get; set; } = new();
        public int SelectedQuantity { get; set; } = 1;
        public List<long> SelectedComplementIds { get; set; } = new();
    }
}
