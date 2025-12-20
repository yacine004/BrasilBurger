using Microsoft.EntityFrameworkCore;
using BrasilBurger.Web.Models;

namespace BrasilBurger.Web.Data;

public class BrasilBurgerContext : DbContext
{
    public BrasilBurgerContext(DbContextOptions<BrasilBurgerContext> options) : base(options) { }

    public DbSet<Zone> Zones { get; set; }
    public DbSet<Quartier> Quartiers { get; set; }
    public DbSet<Client> Clients { get; set; }
    public DbSet<Burger> Burgers { get; set; }
    public DbSet<Menu> Menus { get; set; }
    public DbSet<Complement> Complements { get; set; }
    public DbSet<MenuBurger> MenuBurgers { get; set; }
    public DbSet<Commande> Commandes { get; set; }
    public DbSet<CommandeBurger> CommandeBurgers { get; set; }
    public DbSet<CommandeMenu> CommandeMenus { get; set; }
    public DbSet<CommandeComplement> CommandeComplements { get; set; }
    public DbSet<Livreur> Livreurs { get; set; }
    public DbSet<Livraison> Livraisons { get; set; }
    public DbSet<Paiement> Paiements { get; set; }
    public DbSet<Configuration> Configurations { get; set; }

    protected override void OnModelCreating(ModelBuilder modelBuilder)
    {
        base.OnModelCreating(modelBuilder);

        // Configure table names to match database schema
        modelBuilder.Entity<Zone>().ToTable("zone");
        modelBuilder.Entity<Zone>().Property(z => z.Id).HasColumnName("id");
        modelBuilder.Entity<Zone>().Property(z => z.Nom).HasColumnName("nom");
        modelBuilder.Entity<Zone>().Property(z => z.PrixLivraison).HasColumnName("prix_livraison");

        modelBuilder.Entity<Quartier>().ToTable("quartier");
        modelBuilder.Entity<Quartier>().Property(q => q.Id).HasColumnName("id");
        modelBuilder.Entity<Quartier>().Property(q => q.Nom).HasColumnName("nom");
        modelBuilder.Entity<Quartier>().Property(q => q.IdZone).HasColumnName("id_zone");

        modelBuilder.Entity<Client>().ToTable("client");
        modelBuilder.Entity<Client>().Property(c => c.Id).HasColumnName("id");
        modelBuilder.Entity<Client>().Property(c => c.Nom).HasColumnName("nom");
        modelBuilder.Entity<Client>().Property(c => c.Prenom).HasColumnName("prenom");
        modelBuilder.Entity<Client>().Property(c => c.Telephone).HasColumnName("telephone");
        modelBuilder.Entity<Client>().Property(c => c.Email).HasColumnName("email");
        modelBuilder.Entity<Client>().Property(c => c.Password).HasColumnName("password");

        modelBuilder.Entity<Burger>().ToTable("burger");
        modelBuilder.Entity<Burger>().Property(b => b.Id).HasColumnName("id");
        modelBuilder.Entity<Burger>().Property(b => b.Nom).HasColumnName("nom");
        modelBuilder.Entity<Burger>().Property(b => b.Prix).HasColumnName("prix");
        modelBuilder.Entity<Burger>().Property(b => b.Image).HasColumnName("image");
        modelBuilder.Entity<Burger>().Property(b => b.Etat).HasColumnName("etat");

        modelBuilder.Entity<Menu>().ToTable("menu");
        modelBuilder.Entity<Menu>().Property(m => m.Id).HasColumnName("id");
        modelBuilder.Entity<Menu>().Property(m => m.Nom).HasColumnName("nom");
        modelBuilder.Entity<Menu>().Property(m => m.Image).HasColumnName("image");
        modelBuilder.Entity<Menu>().Property(m => m.Prix).HasColumnName("prix");

        modelBuilder.Entity<Complement>().ToTable("complement");
        modelBuilder.Entity<Complement>().Property(c => c.Id).HasColumnName("id");
        modelBuilder.Entity<Complement>().Property(c => c.Nom).HasColumnName("nom");
        modelBuilder.Entity<Complement>().Property(c => c.Prix).HasColumnName("prix");
        modelBuilder.Entity<Complement>().Property(c => c.Image).HasColumnName("image");
        modelBuilder.Entity<Complement>().Property(c => c.Etat).HasColumnName("etat");

        modelBuilder.Entity<MenuBurger>().ToTable("menu_burger");
        modelBuilder.Entity<MenuBurger>().Property(mb => mb.Id).HasColumnName("id");
        modelBuilder.Entity<MenuBurger>().Property(mb => mb.IdMenu).HasColumnName("id_menu");
        modelBuilder.Entity<MenuBurger>().Property(mb => mb.IdBurger).HasColumnName("id_burger");

        modelBuilder.Entity<Commande>().ToTable("commande");
        modelBuilder.Entity<Commande>().Property(c => c.Id).HasColumnName("id");
        modelBuilder.Entity<Commande>().Property(c => c.Date).HasColumnName("date");
        modelBuilder.Entity<Commande>().Property(c => c.Etat).HasColumnName("etat");
        modelBuilder.Entity<Commande>().Property(c => c.Mode).HasColumnName("mode");
        modelBuilder.Entity<Commande>().Property(c => c.Montant).HasColumnName("montant");
        modelBuilder.Entity<Commande>().Property(c => c.IdClient).HasColumnName("id_client");

        modelBuilder.Entity<CommandeBurger>().ToTable("commande_burger");
        modelBuilder.Entity<CommandeBurger>().Property(cb => cb.Id).HasColumnName("id");
        modelBuilder.Entity<CommandeBurger>().Property(cb => cb.IdCommande).HasColumnName("id_commande");
        modelBuilder.Entity<CommandeBurger>().Property(cb => cb.IdBurger).HasColumnName("id_burger");
        modelBuilder.Entity<CommandeBurger>().Property(cb => cb.Qte).HasColumnName("qte");

        modelBuilder.Entity<CommandeMenu>().ToTable("commande_menu");
        modelBuilder.Entity<CommandeMenu>().Property(cm => cm.Id).HasColumnName("id");
        modelBuilder.Entity<CommandeMenu>().Property(cm => cm.IdCommande).HasColumnName("id_commande");
        modelBuilder.Entity<CommandeMenu>().Property(cm => cm.IdMenu).HasColumnName("id_menu");
        modelBuilder.Entity<CommandeMenu>().Property(cm => cm.Qte).HasColumnName("qte");

        modelBuilder.Entity<CommandeComplement>().ToTable("commande_complement");
        modelBuilder.Entity<CommandeComplement>().Property(cc => cc.Id).HasColumnName("id");
        modelBuilder.Entity<CommandeComplement>().Property(cc => cc.IdCommande).HasColumnName("id_commande");
        modelBuilder.Entity<CommandeComplement>().Property(cc => cc.IdComplement).HasColumnName("id_complement");
        modelBuilder.Entity<CommandeComplement>().Property(cc => cc.Qte).HasColumnName("qte");

        modelBuilder.Entity<Livreur>().ToTable("livreur");
        modelBuilder.Entity<Livreur>().Property(l => l.Id).HasColumnName("id");
        modelBuilder.Entity<Livreur>().Property(l => l.Nom).HasColumnName("nom");
        modelBuilder.Entity<Livreur>().Property(l => l.Prenom).HasColumnName("prenom");
        modelBuilder.Entity<Livreur>().Property(l => l.Telephone).HasColumnName("telephone");

        modelBuilder.Entity<Livraison>().ToTable("livraison");
        modelBuilder.Entity<Livraison>().Property(l => l.Id).HasColumnName("id");
        modelBuilder.Entity<Livraison>().Property(l => l.IdCommande).HasColumnName("id_commande");
        modelBuilder.Entity<Livraison>().Property(l => l.IdLivreur).HasColumnName("id_livreur");

        modelBuilder.Entity<Paiement>().ToTable("paiement");
        modelBuilder.Entity<Paiement>().Property(p => p.Id).HasColumnName("id");
        modelBuilder.Entity<Paiement>().Property(p => p.Date).HasColumnName("date");
        modelBuilder.Entity<Paiement>().Property(p => p.Montant).HasColumnName("montant");
        modelBuilder.Entity<Paiement>().Property(p => p.Mode).HasColumnName("mode");
        modelBuilder.Entity<Paiement>().Property(p => p.IdCommande).HasColumnName("id_commande");

        // Quartier -> Zone (1-N)
        modelBuilder.Entity<Quartier>()
            .HasOne(q => q.Zone)
            .WithMany(z => z.Quartiers)
            .HasForeignKey(q => q.IdZone)
            .OnDelete(DeleteBehavior.Cascade);

        // Commande -> Client (N-1)
        modelBuilder.Entity<Commande>()
            .HasOne(c => c.Client)
            .WithMany(cl => cl.Commandes)
            .HasForeignKey(c => c.IdClient)
            .OnDelete(DeleteBehavior.Cascade);

        // CommandeBurger (N-N)
        modelBuilder.Entity<CommandeBurger>()
            .HasOne(cb => cb.Commande)
            .WithMany(c => c.CommandeBurgers)
            .HasForeignKey(cb => cb.IdCommande)
            .OnDelete(DeleteBehavior.Cascade);

        modelBuilder.Entity<CommandeBurger>()
            .HasOne(cb => cb.Burger)
            .WithMany(b => b.CommandeBurgers)
            .HasForeignKey(cb => cb.IdBurger)
            .OnDelete(DeleteBehavior.Cascade);

        // CommandeMenu (N-N)
        modelBuilder.Entity<CommandeMenu>()
            .HasOne(cm => cm.Commande)
            .WithMany(c => c.CommandeMenus)
            .HasForeignKey(cm => cm.IdCommande)
            .OnDelete(DeleteBehavior.Cascade);

        modelBuilder.Entity<CommandeMenu>()
            .HasOne(cm => cm.Menu)
            .WithMany(m => m.CommandeMenus)
            .HasForeignKey(cm => cm.IdMenu)
            .OnDelete(DeleteBehavior.Cascade);

        // CommandeComplement (N-N)
        modelBuilder.Entity<CommandeComplement>()
            .HasOne(cc => cc.Commande)
            .WithMany(c => c.CommandeComplements)
            .HasForeignKey(cc => cc.IdCommande)
            .OnDelete(DeleteBehavior.Cascade);

        modelBuilder.Entity<CommandeComplement>()
            .HasOne(cc => cc.Complement)
            .WithMany(c => c.CommandeComplements)
            .HasForeignKey(cc => cc.IdComplement)
            .OnDelete(DeleteBehavior.Cascade);

        // MenuBurger (N-N)
        modelBuilder.Entity<MenuBurger>()
            .HasOne(mb => mb.Menu)
            .WithMany(m => m.MenuBurgers)
            .HasForeignKey(mb => mb.IdMenu)
            .OnDelete(DeleteBehavior.Cascade);

        modelBuilder.Entity<MenuBurger>()
            .HasOne(mb => mb.Burger)
            .WithMany(b => b.MenuBurgers)
            .HasForeignKey(mb => mb.IdBurger)
            .OnDelete(DeleteBehavior.Cascade);

        // Livraison -> Commande (1-1)
        modelBuilder.Entity<Livraison>()
            .HasOne(l => l.Commande)
            .WithOne(c => c.Livraison)
            .HasForeignKey<Livraison>(l => l.IdCommande)
            .OnDelete(DeleteBehavior.Cascade);

        modelBuilder.Entity<Livraison>()
            .HasOne(l => l.Livreur)
            .WithMany(li => li.Livraisons)
            .HasForeignKey(l => l.IdLivreur)
            .OnDelete(DeleteBehavior.Cascade);

        // Paiement -> Commande (1-1)
        modelBuilder.Entity<Paiement>()
            .HasOne(p => p.Commande)
            .WithOne(c => c.Paiement)
            .HasForeignKey<Paiement>(p => p.IdCommande)
            .OnDelete(DeleteBehavior.Cascade);

        // Configuration table
        modelBuilder.Entity<Configuration>().ToTable("configuration");
        modelBuilder.Entity<Configuration>().Property(c => c.Id).HasColumnName("id");
        modelBuilder.Entity<Configuration>().Property(c => c.Cle).HasColumnName("cle");
        modelBuilder.Entity<Configuration>().Property(c => c.Valeur).HasColumnName("valeur");
        modelBuilder.Entity<Configuration>().HasIndex(c => c.Cle).IsUnique();
    }
}
