using System.Net.Http.Headers;
using System.Text;
using System.Text.Json;
using BrasilBurger.Web.Models.DTOs;
using Polly;
using Polly.CircuitBreaker;
using Polly.Retry;

namespace BrasilBurger.Web.Services
{
    public class ApiService : IApiService
    {
        private readonly HttpClient _httpClient;
        private readonly ILogger<ApiService> _logger;
        private readonly string _baseUrl;
        private readonly string _username;
        private readonly string _password;

        public ApiService(HttpClient httpClient, IConfiguration configuration, ILogger<ApiService> logger)
        {
            _httpClient = httpClient;
            _logger = logger;
            _baseUrl = configuration["ApiSettings:BaseUrl"] ?? "http://localhost:8080/api";
            _username = configuration["ApiSettings:Username"] ?? "user";
            _password = configuration["ApiSettings:Password"] ?? "64830805-f8d5-4e98-b840-4f44e225347d";
            
            // Ajouter l'authentification Basic
            var credentials = Convert.ToBase64String(Encoding.ASCII.GetBytes($"{_username}:{_password}"));
            _httpClient.DefaultRequestHeaders.Authorization = 
                new AuthenticationHeaderValue("Basic", credentials);
        }

        // =============== BURGERS ===============
        public async Task<List<BurgerDto>> GetAllBurgersAsync()
        {
            try
            {
                var response = await _httpClient.GetAsync($"{_baseUrl}/burgers");
                response.EnsureSuccessStatusCode();
                var json = await response.Content.ReadAsStringAsync();
                return JsonSerializer.Deserialize<List<BurgerDto>>(json) ?? new();
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur lors de GetAllBurgersAsync: {ex.Message}");
                return new();
            }
        }

        public async Task<BurgerDto?> GetBurgerByIdAsync(long id)
        {
            try
            {
                var response = await _httpClient.GetAsync($"{_baseUrl}/burgers/{id}");
                response.EnsureSuccessStatusCode();
                var json = await response.Content.ReadAsStringAsync();
                return JsonSerializer.Deserialize<BurgerDto>(json);
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur lors de GetBurgerByIdAsync({id}): {ex.Message}");
                return null;
            }
        }

        public async Task<BurgerDto> CreateBurgerAsync(BurgerDto burger)
        {
            try
            {
                var json = JsonSerializer.Serialize(burger);
                var content = new StringContent(json, System.Text.Encoding.UTF8, "application/json");
                var response = await _httpClient.PostAsync($"{_baseUrl}/burgers", content);
                response.EnsureSuccessStatusCode();
                var responseJson = await response.Content.ReadAsStringAsync();
                return JsonSerializer.Deserialize<BurgerDto>(responseJson) ?? burger;
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur lors de CreateBurgerAsync: {ex.Message}");
                throw;
            }
        }

        public async Task<BurgerDto> UpdateBurgerAsync(long id, BurgerDto burger)
        {
            try
            {
                var json = JsonSerializer.Serialize(burger);
                var content = new StringContent(json, System.Text.Encoding.UTF8, "application/json");
                var response = await _httpClient.PutAsync($"{_baseUrl}/burgers/{id}", content);
                response.EnsureSuccessStatusCode();
                var responseJson = await response.Content.ReadAsStringAsync();
                return JsonSerializer.Deserialize<BurgerDto>(responseJson) ?? burger;
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur lors de UpdateBurgerAsync({id}): {ex.Message}");
                throw;
            }
        }

        public async Task DeleteBurgerAsync(long id)
        {
            try
            {
                var response = await _httpClient.DeleteAsync($"{_baseUrl}/burgers/{id}");
                response.EnsureSuccessStatusCode();
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur lors de DeleteBurgerAsync({id}): {ex.Message}");
                throw;
            }
        }

        // =============== MENUS ===============
        public async Task<List<MenuDto>> GetAllMenusAsync()
        {
            try
            {
                var response = await _httpClient.GetAsync($"{_baseUrl}/menus");
                response.EnsureSuccessStatusCode();
                var json = await response.Content.ReadAsStringAsync();
                return JsonSerializer.Deserialize<List<MenuDto>>(json) ?? new();
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur lors de GetAllMenusAsync: {ex.Message}");
                return new();
            }
        }

        public async Task<MenuDto?> GetMenuByIdAsync(long id)
        {
            try
            {
                var response = await _httpClient.GetAsync($"{_baseUrl}/menus/{id}");
                response.EnsureSuccessStatusCode();
                var json = await response.Content.ReadAsStringAsync();
                return JsonSerializer.Deserialize<MenuDto>(json);
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur lors de GetMenuByIdAsync({id}): {ex.Message}");
                return null;
            }
        }

        // =============== COMPLEMENTS ===============
        public async Task<List<ComplementDto>> GetAllComplementsAsync()
        {
            try
            {
                var response = await _httpClient.GetAsync($"{_baseUrl}/complements");
                response.EnsureSuccessStatusCode();
                var json = await response.Content.ReadAsStringAsync();
                return JsonSerializer.Deserialize<List<ComplementDto>>(json) ?? new();
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur lors de GetAllComplementsAsync: {ex.Message}");
                return new();
            }
        }

        public async Task<ComplementDto?> GetComplementByIdAsync(long id)
        {
            try
            {
                var response = await _httpClient.GetAsync($"{_baseUrl}/complements/{id}");
                response.EnsureSuccessStatusCode();
                var json = await response.Content.ReadAsStringAsync();
                return JsonSerializer.Deserialize<ComplementDto>(json);
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur lors de GetComplementByIdAsync({id}): {ex.Message}");
                return null;
            }
        }

        // =============== CLIENTS ===============
        public async Task<ClientDto?> GetClientByIdAsync(long id)
        {
            try
            {
                var response = await _httpClient.GetAsync($"{_baseUrl}/clients/{id}");
                response.EnsureSuccessStatusCode();
                var json = await response.Content.ReadAsStringAsync();
                return JsonSerializer.Deserialize<ClientDto>(json);
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur lors de GetClientByIdAsync({id}): {ex.Message}");
                return null;
            }
        }

        public async Task<ClientDto> CreateClientAsync(ClientDto client)
        {
            try
            {
                var json = JsonSerializer.Serialize(client);
                var content = new StringContent(json, System.Text.Encoding.UTF8, "application/json");
                var response = await _httpClient.PostAsync($"{_baseUrl}/clients", content);
                response.EnsureSuccessStatusCode();
                var responseJson = await response.Content.ReadAsStringAsync();
                return JsonSerializer.Deserialize<ClientDto>(responseJson) ?? client;
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur lors de CreateClientAsync: {ex.Message}");
                throw;
            }
        }

        public async Task<ClientDto> UpdateClientAsync(long id, ClientDto client)
        {
            try
            {
                var json = JsonSerializer.Serialize(client);
                var content = new StringContent(json, System.Text.Encoding.UTF8, "application/json");
                var response = await _httpClient.PutAsync($"{_baseUrl}/clients/{id}", content);
                response.EnsureSuccessStatusCode();
                var responseJson = await response.Content.ReadAsStringAsync();
                return JsonSerializer.Deserialize<ClientDto>(responseJson) ?? client;
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur lors de UpdateClientAsync({id}): {ex.Message}");
                throw;
            }
        }

        // =============== ZONES ===============
        public async Task<List<ZoneDto>> GetAllZonesAsync()
        {
            try
            {
                var response = await _httpClient.GetAsync($"{_baseUrl}/zones");
                response.EnsureSuccessStatusCode();
                var json = await response.Content.ReadAsStringAsync();
                return JsonSerializer.Deserialize<List<ZoneDto>>(json) ?? new();
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur lors de GetAllZonesAsync: {ex.Message}");
                return new();
            }
        }

        public async Task<ZoneDto?> GetZoneByIdAsync(long id)
        {
            try
            {
                var response = await _httpClient.GetAsync($"{_baseUrl}/zones/{id}");
                response.EnsureSuccessStatusCode();
                var json = await response.Content.ReadAsStringAsync();
                return JsonSerializer.Deserialize<ZoneDto>(json);
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur lors de GetZoneByIdAsync({id}): {ex.Message}");
                return null;
            }
        }

        // =============== ORDERS ===============
        public async Task<List<OrderDto>> GetAllOrdersAsync()
        {
            try
            {
                var response = await _httpClient.GetAsync($"{_baseUrl}/orders");
                response.EnsureSuccessStatusCode();
                var json = await response.Content.ReadAsStringAsync();
                return JsonSerializer.Deserialize<List<OrderDto>>(json) ?? new();
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur lors de GetAllOrdersAsync: {ex.Message}");
                return new();
            }
        }

        public async Task<OrderDto?> GetOrderByIdAsync(long id)
        {
            try
            {
                var response = await _httpClient.GetAsync($"{_baseUrl}/orders/{id}");
                response.EnsureSuccessStatusCode();
                var json = await response.Content.ReadAsStringAsync();
                return JsonSerializer.Deserialize<OrderDto>(json);
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur lors de GetOrderByIdAsync({id}): {ex.Message}");
                return null;
            }
        }

        public async Task<OrderDto> CreateOrderAsync(OrderDto order)
        {
            try
            {
                var json = JsonSerializer.Serialize(order);
                var content = new StringContent(json, System.Text.Encoding.UTF8, "application/json");
                var response = await _httpClient.PostAsync($"{_baseUrl}/orders", content);
                response.EnsureSuccessStatusCode();
                var responseJson = await response.Content.ReadAsStringAsync();
                return JsonSerializer.Deserialize<OrderDto>(responseJson) ?? order;
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur lors de CreateOrderAsync: {ex.Message}");
                throw;
            }
        }

        public async Task<OrderDto> UpdateOrderAsync(long id, OrderDto order)
        {
            try
            {
                var json = JsonSerializer.Serialize(order);
                var content = new StringContent(json, System.Text.Encoding.UTF8, "application/json");
                var response = await _httpClient.PutAsync($"{_baseUrl}/orders/{id}", content);
                response.EnsureSuccessStatusCode();
                var responseJson = await response.Content.ReadAsStringAsync();
                return JsonSerializer.Deserialize<OrderDto>(responseJson) ?? order;
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur lors de UpdateOrderAsync({id}): {ex.Message}");
                throw;
            }
        }

        public async Task DeleteOrderAsync(long id)
        {
            try
            {
                var response = await _httpClient.DeleteAsync($"{_baseUrl}/orders/{id}");
                response.EnsureSuccessStatusCode();
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur lors de DeleteOrderAsync({id}): {ex.Message}");
                throw;
            }
        }

        // =============== PAYMENTS ===============
        public async Task<PaymentDto> CreatePaymentAsync(PaymentDto payment)
        {
            try
            {
                var json = JsonSerializer.Serialize(payment);
                var content = new StringContent(json, System.Text.Encoding.UTF8, "application/json");
                var response = await _httpClient.PostAsync($"{_baseUrl}/payments", content);
                response.EnsureSuccessStatusCode();
                var responseJson = await response.Content.ReadAsStringAsync();
                return JsonSerializer.Deserialize<PaymentDto>(responseJson) ?? payment;
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur lors de CreatePaymentAsync: {ex.Message}");
                throw;
            }
        }

        public async Task<PaymentDto?> GetPaymentByIdAsync(long id)
        {
            try
            {
                var response = await _httpClient.GetAsync($"{_baseUrl}/payments/{id}");
                response.EnsureSuccessStatusCode();
                var json = await response.Content.ReadAsStringAsync();
                return JsonSerializer.Deserialize<PaymentDto>(json);
            }
            catch (Exception ex)
            {
                _logger.LogError($"Erreur lors de GetPaymentByIdAsync({id}): {ex.Message}");
                return null;
            }
        }
    }
}
