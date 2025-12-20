namespace BrasilBurger.Web.Models;

public class HomeViewModel
{
    public List<Burger>? BurgersRecents { get; set; }
    public List<Menu>? MenusRecents { get; set; }
    public List<Menu>? MenusPopulaires { get; set; }
    public List<Complement>? ComplementsPopulaires { get; set; }
}
