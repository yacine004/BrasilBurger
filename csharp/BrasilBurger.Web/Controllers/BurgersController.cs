using BrasilBurger.Web.Models.ViewModels;
using BrasilBurger.Web.Services;
using Microsoft.AspNetCore.Mvc;

namespace BrasilBurger.Web.Controllers
{
    public class BurgersController : Controller
    {
        private readonly IApiService _apiService;
        private readonly ILogger<BurgersController> _logger;

        public BurgersController(IApiService apiService, ILogger<BurgersController> logger)
        {
            _apiService = apiService;
            _logger = logger;
        }

        // GET: /Burgers
        public async Task<IActionResult> Index(string? search, long? zoneId, int? minPrice, int? maxPrice)
        {
            try
            {
                var burgers = await _apiService.GetAllBurgersAsync();
                var zones = await _apiService.GetAllZonesAsync();

                // Filtrer
                if (!string.IsNullOrEmpty(search))
                    burgers = burgers.Where(b => b.Nom.Contains(search, StringComparison.OrdinalIgnoreCase)).ToList();

                if (minPrice.HasValue)
                    burgers = burgers.Where(b => b.Prix >= minPrice).ToList();

                if (maxPrice.HasValue)
                    burgers = burgers.Where(b => b.Prix <= maxPrice).ToList();

                var viewModel = new BurgerListViewModel
                {
                    Burgers = burgers,
                    Zones = zones,
                    SearchTerm = search,
                    SelectedZoneId = zoneId,
                    MinPrice = minPrice,
                    MaxPrice = maxPrice
                };

                return View(viewModel);
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur dans BurgersController.Index: {ex.Message}");
                return View(new BurgerListViewModel());
            }
        }

        // GET: /Burgers/5
        public async Task<IActionResult> Details(long id)
        {
            try
            {
                var burger = await _apiService.GetBurgerByIdAsync(id);
                if (burger == null)
                    return NotFound();

                var complements = await _apiService.GetAllComplementsAsync();
                var menus = await _apiService.GetAllMenusAsync();

                var viewModel = new BurgerDetailViewModel
                {
                    Burger = burger,
                    Complements = complements,
                    Menus = menus
                };

                return View(viewModel);
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur dans BurgersController.Details({id}): {ex.Message}");
                return NotFound();
            }
        }
    }
}
