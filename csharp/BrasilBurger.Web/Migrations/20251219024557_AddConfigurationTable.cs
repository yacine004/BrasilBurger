using System;
using Microsoft.EntityFrameworkCore.Migrations;
using Npgsql.EntityFrameworkCore.PostgreSQL.Metadata;

#nullable disable

namespace BrasilBurger.Web.Migrations
{
    /// <inheritdoc />
    public partial class AddConfigurationTable : Migration
    {
        /// <inheritdoc />
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.CreateTable(
                name: "burger",
                columns: table => new
                {
                    id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    nom = table.Column<string>(type: "text", nullable: false),
                    prix = table.Column<decimal>(type: "numeric", nullable: false),
                    image = table.Column<string>(type: "text", nullable: true),
                    etat = table.Column<bool>(type: "boolean", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_burger", x => x.id);
                });

            migrationBuilder.CreateTable(
                name: "client",
                columns: table => new
                {
                    id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    nom = table.Column<string>(type: "text", nullable: false),
                    prenom = table.Column<string>(type: "text", nullable: false),
                    telephone = table.Column<string>(type: "text", nullable: true),
                    email = table.Column<string>(type: "text", nullable: true),
                    password = table.Column<string>(type: "text", nullable: true)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_client", x => x.id);
                });

            migrationBuilder.CreateTable(
                name: "complement",
                columns: table => new
                {
                    id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    nom = table.Column<string>(type: "text", nullable: false),
                    prix = table.Column<decimal>(type: "numeric", nullable: false),
                    image = table.Column<string>(type: "text", nullable: true),
                    etat = table.Column<bool>(type: "boolean", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_complement", x => x.id);
                });

            migrationBuilder.CreateTable(
                name: "configuration",
                columns: table => new
                {
                    id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    cle = table.Column<string>(type: "character varying(100)", maxLength: 100, nullable: false),
                    valeur = table.Column<string>(type: "text", nullable: true)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_configuration", x => x.id);
                });

            migrationBuilder.CreateTable(
                name: "livreur",
                columns: table => new
                {
                    id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    nom = table.Column<string>(type: "text", nullable: true),
                    prenom = table.Column<string>(type: "text", nullable: true),
                    telephone = table.Column<string>(type: "text", nullable: true)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_livreur", x => x.id);
                });

            migrationBuilder.CreateTable(
                name: "menu",
                columns: table => new
                {
                    id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    nom = table.Column<string>(type: "text", nullable: false),
                    image = table.Column<string>(type: "text", nullable: true),
                    prix = table.Column<decimal>(type: "numeric", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_menu", x => x.id);
                });

            migrationBuilder.CreateTable(
                name: "zone",
                columns: table => new
                {
                    id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    nom = table.Column<string>(type: "text", nullable: false),
                    prix_livraison = table.Column<decimal>(type: "numeric", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_zone", x => x.id);
                });

            migrationBuilder.CreateTable(
                name: "commande",
                columns: table => new
                {
                    id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    date = table.Column<DateTime>(type: "timestamp with time zone", nullable: false),
                    etat = table.Column<string>(type: "text", nullable: true),
                    mode = table.Column<string>(type: "text", nullable: true),
                    montant = table.Column<decimal>(type: "numeric", nullable: false),
                    id_client = table.Column<int>(type: "integer", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_commande", x => x.id);
                    table.ForeignKey(
                        name: "FK_commande_client_id_client",
                        column: x => x.id_client,
                        principalTable: "client",
                        principalColumn: "id",
                        onDelete: ReferentialAction.Cascade);
                });

            migrationBuilder.CreateTable(
                name: "menu_burger",
                columns: table => new
                {
                    id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    id_menu = table.Column<int>(type: "integer", nullable: false),
                    id_burger = table.Column<int>(type: "integer", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_menu_burger", x => x.id);
                    table.ForeignKey(
                        name: "FK_menu_burger_burger_id_burger",
                        column: x => x.id_burger,
                        principalTable: "burger",
                        principalColumn: "id",
                        onDelete: ReferentialAction.Cascade);
                    table.ForeignKey(
                        name: "FK_menu_burger_menu_id_menu",
                        column: x => x.id_menu,
                        principalTable: "menu",
                        principalColumn: "id",
                        onDelete: ReferentialAction.Cascade);
                });

            migrationBuilder.CreateTable(
                name: "quartier",
                columns: table => new
                {
                    id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    nom = table.Column<string>(type: "text", nullable: false),
                    id_zone = table.Column<int>(type: "integer", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_quartier", x => x.id);
                    table.ForeignKey(
                        name: "FK_quartier_zone_id_zone",
                        column: x => x.id_zone,
                        principalTable: "zone",
                        principalColumn: "id",
                        onDelete: ReferentialAction.Cascade);
                });

            migrationBuilder.CreateTable(
                name: "commande_burger",
                columns: table => new
                {
                    id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    id_commande = table.Column<int>(type: "integer", nullable: false),
                    id_burger = table.Column<int>(type: "integer", nullable: false),
                    qte = table.Column<int>(type: "integer", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_commande_burger", x => x.id);
                    table.ForeignKey(
                        name: "FK_commande_burger_burger_id_burger",
                        column: x => x.id_burger,
                        principalTable: "burger",
                        principalColumn: "id",
                        onDelete: ReferentialAction.Cascade);
                    table.ForeignKey(
                        name: "FK_commande_burger_commande_id_commande",
                        column: x => x.id_commande,
                        principalTable: "commande",
                        principalColumn: "id",
                        onDelete: ReferentialAction.Cascade);
                });

            migrationBuilder.CreateTable(
                name: "commande_complement",
                columns: table => new
                {
                    id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    id_commande = table.Column<int>(type: "integer", nullable: false),
                    id_complement = table.Column<int>(type: "integer", nullable: false),
                    qte = table.Column<int>(type: "integer", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_commande_complement", x => x.id);
                    table.ForeignKey(
                        name: "FK_commande_complement_commande_id_commande",
                        column: x => x.id_commande,
                        principalTable: "commande",
                        principalColumn: "id",
                        onDelete: ReferentialAction.Cascade);
                    table.ForeignKey(
                        name: "FK_commande_complement_complement_id_complement",
                        column: x => x.id_complement,
                        principalTable: "complement",
                        principalColumn: "id",
                        onDelete: ReferentialAction.Cascade);
                });

            migrationBuilder.CreateTable(
                name: "commande_menu",
                columns: table => new
                {
                    id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    id_commande = table.Column<int>(type: "integer", nullable: false),
                    id_menu = table.Column<int>(type: "integer", nullable: false),
                    qte = table.Column<int>(type: "integer", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_commande_menu", x => x.id);
                    table.ForeignKey(
                        name: "FK_commande_menu_commande_id_commande",
                        column: x => x.id_commande,
                        principalTable: "commande",
                        principalColumn: "id",
                        onDelete: ReferentialAction.Cascade);
                    table.ForeignKey(
                        name: "FK_commande_menu_menu_id_menu",
                        column: x => x.id_menu,
                        principalTable: "menu",
                        principalColumn: "id",
                        onDelete: ReferentialAction.Cascade);
                });

            migrationBuilder.CreateTable(
                name: "livraison",
                columns: table => new
                {
                    id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    id_commande = table.Column<int>(type: "integer", nullable: false),
                    id_livreur = table.Column<int>(type: "integer", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_livraison", x => x.id);
                    table.ForeignKey(
                        name: "FK_livraison_commande_id_commande",
                        column: x => x.id_commande,
                        principalTable: "commande",
                        principalColumn: "id",
                        onDelete: ReferentialAction.Cascade);
                    table.ForeignKey(
                        name: "FK_livraison_livreur_id_livreur",
                        column: x => x.id_livreur,
                        principalTable: "livreur",
                        principalColumn: "id",
                        onDelete: ReferentialAction.Cascade);
                });

            migrationBuilder.CreateTable(
                name: "paiement",
                columns: table => new
                {
                    id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    date = table.Column<DateTime>(type: "timestamp with time zone", nullable: false),
                    montant = table.Column<decimal>(type: "numeric", nullable: false),
                    mode = table.Column<string>(type: "text", nullable: true),
                    id_commande = table.Column<int>(type: "integer", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_paiement", x => x.id);
                    table.ForeignKey(
                        name: "FK_paiement_commande_id_commande",
                        column: x => x.id_commande,
                        principalTable: "commande",
                        principalColumn: "id",
                        onDelete: ReferentialAction.Cascade);
                });

            migrationBuilder.CreateIndex(
                name: "IX_commande_id_client",
                table: "commande",
                column: "id_client");

            migrationBuilder.CreateIndex(
                name: "IX_commande_burger_id_burger",
                table: "commande_burger",
                column: "id_burger");

            migrationBuilder.CreateIndex(
                name: "IX_commande_burger_id_commande",
                table: "commande_burger",
                column: "id_commande");

            migrationBuilder.CreateIndex(
                name: "IX_commande_complement_id_commande",
                table: "commande_complement",
                column: "id_commande");

            migrationBuilder.CreateIndex(
                name: "IX_commande_complement_id_complement",
                table: "commande_complement",
                column: "id_complement");

            migrationBuilder.CreateIndex(
                name: "IX_commande_menu_id_commande",
                table: "commande_menu",
                column: "id_commande");

            migrationBuilder.CreateIndex(
                name: "IX_commande_menu_id_menu",
                table: "commande_menu",
                column: "id_menu");

            migrationBuilder.CreateIndex(
                name: "IX_configuration_cle",
                table: "configuration",
                column: "cle",
                unique: true);

            migrationBuilder.CreateIndex(
                name: "IX_livraison_id_commande",
                table: "livraison",
                column: "id_commande",
                unique: true);

            migrationBuilder.CreateIndex(
                name: "IX_livraison_id_livreur",
                table: "livraison",
                column: "id_livreur");

            migrationBuilder.CreateIndex(
                name: "IX_menu_burger_id_burger",
                table: "menu_burger",
                column: "id_burger");

            migrationBuilder.CreateIndex(
                name: "IX_menu_burger_id_menu",
                table: "menu_burger",
                column: "id_menu");

            migrationBuilder.CreateIndex(
                name: "IX_paiement_id_commande",
                table: "paiement",
                column: "id_commande",
                unique: true);

            migrationBuilder.CreateIndex(
                name: "IX_quartier_id_zone",
                table: "quartier",
                column: "id_zone");
        }

        /// <inheritdoc />
        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DropTable(
                name: "commande_burger");

            migrationBuilder.DropTable(
                name: "commande_complement");

            migrationBuilder.DropTable(
                name: "commande_menu");

            migrationBuilder.DropTable(
                name: "configuration");

            migrationBuilder.DropTable(
                name: "livraison");

            migrationBuilder.DropTable(
                name: "menu_burger");

            migrationBuilder.DropTable(
                name: "paiement");

            migrationBuilder.DropTable(
                name: "quartier");

            migrationBuilder.DropTable(
                name: "complement");

            migrationBuilder.DropTable(
                name: "livreur");

            migrationBuilder.DropTable(
                name: "burger");

            migrationBuilder.DropTable(
                name: "menu");

            migrationBuilder.DropTable(
                name: "commande");

            migrationBuilder.DropTable(
                name: "zone");

            migrationBuilder.DropTable(
                name: "client");
        }
    }
}
