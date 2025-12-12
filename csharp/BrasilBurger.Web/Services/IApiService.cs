using BrasilBurger.Web.Models.DTOs;

namespace BrasilBurger.Web.Services
{
    public interface IApiService
    {
        // Burgers
        Task<List<BurgerDto>> GetAllBurgersAsync();
        Task<BurgerDto?> GetBurgerByIdAsync(long id);
        Task<BurgerDto> CreateBurgerAsync(BurgerDto burger);
        Task<BurgerDto> UpdateBurgerAsync(long id, BurgerDto burger);
        Task DeleteBurgerAsync(long id);

        // Menus
        Task<List<MenuDto>> GetAllMenusAsync();
        Task<MenuDto?> GetMenuByIdAsync(long id);

        // Complements
        Task<List<ComplementDto>> GetAllComplementsAsync();
        Task<ComplementDto?> GetComplementByIdAsync(long id);

        // Clients
        Task<ClientDto?> GetClientByIdAsync(long id);
        Task<ClientDto> CreateClientAsync(ClientDto client);
        Task<ClientDto> UpdateClientAsync(long id, ClientDto client);

        // Zones
        Task<List<ZoneDto>> GetAllZonesAsync();
        Task<ZoneDto?> GetZoneByIdAsync(long id);

        // Orders
        Task<List<OrderDto>> GetAllOrdersAsync();
        Task<OrderDto?> GetOrderByIdAsync(long id);
        Task<OrderDto> CreateOrderAsync(OrderDto order);
        Task<OrderDto> UpdateOrderAsync(long id, OrderDto order);
        Task DeleteOrderAsync(long id);

        // Payments
        Task<PaymentDto> CreatePaymentAsync(PaymentDto payment);
        Task<PaymentDto?> GetPaymentByIdAsync(long id);
    }
}
