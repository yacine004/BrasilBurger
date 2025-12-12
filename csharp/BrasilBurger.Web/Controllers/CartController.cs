using BrasilBurger.Web.Extensions;
using BrasilBurger.Web.Models.ViewModels;
using BrasilBurger.Web.Services;
using Microsoft.AspNetCore.Mvc;

namespace BrasilBurger.Web.Controllers
{
    public class CartController : Controller
    {
        private readonly IApiService _apiService;
        private readonly ILogger<CartController> _logger;
        private const string CartSessionKey = "Cart";

        public CartController(IApiService apiService, ILogger<CartController> logger)
        {
            _apiService = apiService;
            _logger = logger;
        }

        // GET: /Cart
        public IActionResult Index()
        {
            try
            {
                var cart = GetCartFromSession();
                return View(cart);
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur dans CartController.Index: {ex.Message}");
                return View(new CartViewModel());
            }
        }

        // POST: /Cart/AddItem
        [HttpPost]
        public async Task<IActionResult> AddItem(long burgerId, int quantity = 1)
        {
            try
            {
                var burger = await _apiService.GetBurgerByIdAsync(burgerId);
                if (burger == null)
                    return NotFound();

                var cart = GetCartFromSession();
                var item = new CartItemViewModel
                {
                    BurgerId = burger.Id,
                    BurgerName = burger.Nom,
                    Price = burger.Prix,
                    Quantity = quantity
                };

                var existingItem = cart.Items.FirstOrDefault(x => x.BurgerId == burgerId);
                if (existingItem != null)
                    existingItem.Quantity += quantity;
                else
                    cart.Items.Add(item);

                SaveCartToSession(cart);
                return RedirectToAction("Index");
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur dans CartController.AddItem: {ex.Message}");
                return RedirectToAction("Index");
            }
        }

        // POST: /Cart/RemoveItem
        [HttpPost]
        public IActionResult RemoveItem(long burgerId)
        {
            try
            {
                var cart = GetCartFromSession();
                var item = cart.Items.FirstOrDefault(x => x.BurgerId == burgerId);
                if (item != null)
                    cart.Items.Remove(item);

                SaveCartToSession(cart);
                return RedirectToAction("Index");
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur dans CartController.RemoveItem: {ex.Message}");
                return RedirectToAction("Index");
            }
        }

        // POST: /Cart/Clear
        [HttpPost]
        public IActionResult Clear()
        {
            try
            {
                HttpContext.Session.Remove(CartSessionKey);
                return RedirectToAction("Index");
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur dans CartController.Clear: {ex.Message}");
                return RedirectToAction("Index");
            }
        }

        private CartViewModel GetCartFromSession()
        {
            if (HttpContext.Session.TryGetValue(CartSessionKey, out _))
            {
                var cart = HttpContext.Session.GetObjectFromJson<CartViewModel>(CartSessionKey);
                return cart ?? new CartViewModel();
            }
            return new CartViewModel();
        }

        private void SaveCartToSession(CartViewModel cart)
        {
            HttpContext.Session.SetObjectAsJson(CartSessionKey, cart);
        }
    }
}
