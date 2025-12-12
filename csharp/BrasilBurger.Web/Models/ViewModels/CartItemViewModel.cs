namespace BrasilBurger.Web.Models.ViewModels
{
    public class CartItemViewModel
    {
        public long BurgerId { get; set; }
        public string BurgerName { get; set; } = string.Empty;
        public decimal Price { get; set; }
        public int Quantity { get; set; }
        public List<long> ComplementIds { get; set; } = new();
        public decimal Subtotal => Price * Quantity;
    }
}
