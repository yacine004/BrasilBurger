using BrasilBurger.Web.Models.ViewModels;
using BrasilBurger.Web.Services;
using Microsoft.AspNetCore.Mvc;

namespace BrasilBurger.Web.Controllers
{
    public class OrdersController : Controller
    {
        private readonly IApiService _apiService;
        private readonly ILogger<OrdersController> _logger;

        public OrdersController(IApiService apiService, ILogger<OrdersController> logger)
        {
            _apiService = apiService;
            _logger = logger;
        }

        // GET: /Orders
        public async Task<IActionResult> Index()
        {
            try
            {
                var orders = await _apiService.GetAllOrdersAsync();
                return View(orders);
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur dans OrdersController.Index: {ex.Message}");
                return View(new List<object>());
            }
        }

        // GET: /Orders/5
        public async Task<IActionResult> Details(long id)
        {
            try
            {
                var order = await _apiService.GetOrderByIdAsync(id);
                if (order == null)
                    return NotFound();

                var burgers = await _apiService.GetAllBurgersAsync();
                var complements = await _apiService.GetAllComplementsAsync();

                var viewModel = new OrderViewModel
                {
                    Order = order,
                    Burgers = burgers,
                    Complements = complements
                };

                return View(viewModel);
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur dans OrdersController.Details({id}): {ex.Message}");
                return NotFound();
            }
        }
    }
}
