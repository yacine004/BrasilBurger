namespace BrasilBurger.Web.Models.ViewModels
{
    public class CartViewModel
    {
        public List<CartItemViewModel> Items { get; set; } = new();
        public decimal Total => Items.Sum(i => i.Subtotal);
        public int ItemCount => Items.Sum(i => i.Quantity);
        public string? PromoCode { get; set; }
        public decimal Discount { get; set; }
        public decimal TotalAfterDiscount => Total - Discount;
    }
}
