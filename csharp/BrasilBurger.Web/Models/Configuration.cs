using System.ComponentModel.DataAnnotations;

namespace BrasilBurger.Web.Models
{
    public class Configuration
    {
        [Key]
        public int Id { get; set; }

        [Required]
        [MaxLength(100)]
        public required string Cle { get; set; } // Exemple: "HeroImage", "SiteTitle"

        public string? Valeur { get; set; } // L'URL de l'image ou autre valeur
    }
}
