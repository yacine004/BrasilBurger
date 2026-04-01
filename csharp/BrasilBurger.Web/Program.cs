using BrasilBurger.Web.Data;
using BrasilBurger.Web.Services;
using Microsoft.EntityFrameworkCore;

var builder = WebApplication.CreateBuilder(args);

// Add services to the container.
builder.Services.AddControllersWithViews();

// Add Cloudinary Service
builder.Services.AddScoped<CloudinaryService>();

// Add Session
builder.Services.AddSession(options =>
{
    options.IdleTimeout = TimeSpan.FromMinutes(30);
    options.Cookie.HttpOnly = true;
    options.Cookie.IsEssential = true;
});

// Add PostgreSQL DbContext
var connectionString = builder.Configuration.GetConnectionString("DefaultConnection");

// Diagnostic logging: write connection string (mask password) to logs
var masked = connectionString;
if (!string.IsNullOrEmpty(masked))
{
    var pwdIndex = masked.IndexOf("Password", StringComparison.OrdinalIgnoreCase);
    if (pwdIndex >= 0)
    {
        var semicolonIndex = masked.IndexOf(';', pwdIndex);
        if (semicolonIndex > pwdIndex)
        {
            masked = masked.Substring(0, pwdIndex) + "Password=*****" + masked.Substring(semicolonIndex);
        }
    }
}
var loggerFactory = LoggerFactory.Create(builder => builder.AddConsole());
var tempLogger = loggerFactory.CreateLogger("Startup");
tempLogger.LogInformation("Using connection string: {conn}", masked);

builder.Services.AddDbContext<BrasilBurgerContext>(options =>
    options.UseNpgsql(connectionString));

var app = builder.Build();

// Apply any pending migrations automatically
using (var scope = app.Services.CreateScope())
{
    var db = scope.ServiceProvider.GetRequiredService<BrasilBurgerContext>();
    var logger = scope.ServiceProvider.GetRequiredService<ILogger<Program>>();
    try
    {
        logger.LogInformation("Applying database migrations...");
        db.Database.Migrate();
        logger.LogInformation("Migrations applied successfully");

        // Seed data if database is empty
        if (!db.Burgers.Any())
        {
            logger.LogInformation("Seeding database with initial data...");

            // Add burgers
            db.Burgers.AddRange(new[]
            {
                new Burger { Nom = "Burger Classique", Prix = 12.99m, Image = "", Etat = true },
                new Burger { Nom = "Burger Cheese", Prix = 14.50m, Image = "", Etat = true },
                new Burger { Nom = "Burger Bacon", Prix = 16.99m, Image = "", Etat = true },
                new Burger { Nom = "Burger Grillé Premium", Prix = 17.99m, Image = "", Etat = true },
                new Burger { Nom = "Burger Fusion Asiatique", Prix = 16.50m, Image = "", Etat = true },
                new Burger { Nom = "Burger Caramelisé aux Oignons", Prix = 14.50m, Image = "", Etat = true }
            });

            // Add complements
            db.Complements.AddRange(new[]
            {
                new Complement { Nom = "Frites", Prix = 3.99m, Image = "", Etat = true },
                new Complement { Nom = "Coca-Cola", Prix = 2.49m, Image = "", Etat = true },
                new Complement { Nom = "Sprite", Prix = 2.49m, Image = "", Etat = true },
                new Complement { Nom = "Cocktail Tropical", Prix = 4.99m, Image = "", Etat = true }
            });

            // Add menus
            var menu1 = new Menu { Nom = "Menu Classique", Image = "" };
            var menu2 = new Menu { Nom = "Menu Premium", Image = "" };
            db.Menus.AddRange(menu1, menu2);

            db.SaveChanges();

            // Add menu-burger relationships
            db.MenuBurgers.AddRange(
                new MenuBurger { IdMenu = menu1.Id, IdBurger = 1 },
                new MenuBurger { IdMenu = menu1.Id, IdBurger = 2 },
                new MenuBurger { IdMenu = menu2.Id, IdBurger = 3 },
                new MenuBurger { IdMenu = menu2.Id, IdBurger = 4 }
            );

            db.SaveChanges();
            logger.LogInformation("Database seeded successfully");
        }
        else
        {
            logger.LogInformation("Database already contains data, skipping seed");
        }
    }
    catch (Exception ex)
    {
        logger.LogError(ex, "An error occurred while migrating or seeding the database.");
        throw; // Re-throw to prevent app startup if database is broken
    }
}

// Configure the HTTP request pipeline.
if (!app.Environment.IsDevelopment())
{
    app.UseExceptionHandler("/Home/Error");
    app.UseHsts();
}

app.UseHttpsRedirection();
app.UseRouting();

// Add Session middleware
app.UseSession();

app.UseAuthorization();

app.MapStaticAssets();

app.MapControllerRoute(
    name: "default",
    pattern: "{controller=Home}/{action=Index}/{id?}")
    .WithStaticAssets();


app.Run();
