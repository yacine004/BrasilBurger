package com.brasilburger;

import com.brasilburger.config.DatabaseConfig;
import com.brasilburger.dao.BurgerDAO;
import com.brasilburger.model.Burger;
import com.brasilburger.service.CloudinaryService;

import java.io.File;
import java.sql.SQLException;
import java.util.List;
import java.util.Scanner;

public class Main {
    
    private static final Scanner scanner = new Scanner(System.in);
    private static final BurgerDAO burgerDAO = new BurgerDAO();
    private static final CloudinaryService cloudinaryService = new CloudinaryService();
    
    public static void main(String[] args) {
        System.out.println("==========================================");
        System.out.println("   BRASIL BURGER - Application Console   ");
        System.out.println("==========================================");
        System.out.println();
        
        try {
            // Test connexion base de donnees
            DatabaseConfig.getConnection();
            
            boolean continuer = true;
            while (continuer) {
                afficherMenuPrincipal();
                int choix = lireEntier();
                
                switch (choix) {
                    case 1 -> gererBurgers();
                    case 2 -> gererMenus();
                    case 3 -> gererComplements();
                    case 4 -> {
                        System.out.println("\n[*] Au revoir !");
                        continuer = false;
                    }
                    default -> System.out.println("[X] Choix invalide");
                }
            }
            
        } catch (Exception e) {
            System.err.println("[X] Erreur : " + e.getMessage());
            e.printStackTrace();
        } finally {
            DatabaseConfig.closeConnection();
            scanner.close();
        }
    }
    
    private static void afficherMenuPrincipal() {
        System.out.println("\n=============== MENU PRINCIPAL ===============");
        System.out.println("1. Gerer les Burgers");
        System.out.println("2. Gerer les Menus");
        System.out.println("3. Gerer les Complements");
        System.out.println("4. Quitter");
        System.out.print("Votre choix : ");
    }
    
    private static void gererBurgers() {
        boolean continuer = true;
        while (continuer) {
            System.out.println("\n----------- GESTION BURGERS -----------");
            System.out.println("1. Afficher tous les burgers");
            System.out.println("2. Ajouter un burger");
            System.out.println("3. Modifier un burger");
            System.out.println("4. Supprimer un burger");
            System.out.println("5. Upload image pour un burger");
            System.out.println("6. Retour");
            System.out.print("Votre choix : ");
            
            int choix = lireEntier();
            
            try {
                switch (choix) {
                    case 1 -> afficherBurgers();
                    case 2 -> ajouterBurger();
                    case 3 -> modifierBurger();
                    case 4 -> supprimerBurger();
                    case 5 -> uploadImageBurger();
                    case 6 -> continuer = false;
                    default -> System.out.println("[X] Choix invalide");
                }
            } catch (SQLException e) {
                System.err.println("[X] Erreur base de donnees : " + e.getMessage());
            } catch (Exception e) {
                System.err.println("[X] Erreur : " + e.getMessage());
            }
        }
    }
    
    private static void afficherBurgers() throws SQLException {
        List<Burger> burgers = burgerDAO.findAll();
        
        if (burgers.isEmpty()) {
            System.out.println("\nAucun burger trouve.");
            return;
        }
        
        System.out.println("\n[+] Liste des burgers (" + burgers.size() + ") :");
        System.out.println("----------------------------------------");
        for (Burger burger : burgers) {
            System.out.println(burger);
        }
        System.out.println("----------------------------------------");
    }
    
    private static void ajouterBurger() throws SQLException {
        System.out.println("\n[+] AJOUTER UN BURGER");
        
        System.out.print("Nom : ");
        String nom = scanner.nextLine();
        
        System.out.print("Description : ");
        String description = scanner.nextLine();
        
        System.out.print("Prix (FCFA) : ");
        double prix = lireDouble();
        
        Burger burger = new Burger(nom, description, prix, null);
        burgerDAO.create(burger);
        
        System.out.println("[OK] Burger ajoute avec succes ! ID: " + burger.getId());
    }
    
    private static void modifierBurger() throws SQLException {
        System.out.print("\n[*] ID du burger a modifier : ");
        int id = lireEntier();
        
        Burger burger = burgerDAO.findById(id);
        if (burger == null) {
            System.out.println("[X] Burger non trouve");
            return;
        }
        
        System.out.println("Burger actuel : " + burger);
        System.out.println("\n(Laissez vide pour garder la valeur actuelle)");
        
        System.out.print("Nouveau nom [" + burger.getNom() + "] : ");
        String nom = scanner.nextLine();
        if (!nom.isEmpty()) burger.setNom(nom);
        
        System.out.print("Nouvelle description [" + burger.getDescription() + "] : ");
        String desc = scanner.nextLine();
        if (!desc.isEmpty()) burger.setDescription(desc);
        
        System.out.print("Nouveau prix [" + burger.getPrix() + "] : ");
        String prixStr = scanner.nextLine();
        if (!prixStr.isEmpty()) {
            burger.setPrix(Double.parseDouble(prixStr));
        }
        
        burgerDAO.update(burger);
        System.out.println("[OK] Burger modifie avec succes !");
    }
    
    private static void supprimerBurger() throws SQLException {
        System.out.print("\n[-] ID du burger a supprimer : ");
        int id = lireEntier();
        
        Burger burger = burgerDAO.findById(id);
        if (burger == null) {
            System.out.println("[X] Burger non trouve");
            return;
        }
        
        System.out.println("Burger : " + burger);
        System.out.print("Confirmer la suppression ? (o/n) : ");
        String confirm = scanner.nextLine();
        
        if (confirm.equalsIgnoreCase("o")) {
            burgerDAO.delete(id);
            System.out.println("[OK] Burger supprime");
        } else {
            System.out.println("[X] Suppression annulee");
        }
    }
    
    private static void uploadImageBurger() throws Exception {
        System.out.print("\n[^] ID du burger : ");
        int id = lireEntier();
        
        Burger burger = burgerDAO.findById(id);
        if (burger == null) {
            System.out.println("[X] Burger non trouve");
            return;
        }
        
        System.out.print("Chemin de l'image : ");
        String imagePath = scanner.nextLine();
        
        File file = new File(imagePath);
        if (!file.exists()) {
            System.out.println("[X] Fichier non trouve : " + imagePath);
            return;
        }
        
        String imageUrl = cloudinaryService.uploadImage(imagePath, "burgers");
        burger.setImage(imageUrl);
        burgerDAO.update(burger);
        
        System.out.println("[OK] Image uploadee et burger mis a jour !");
        System.out.println("URL : " + imageUrl);
    }
    
    private static void gererMenus() {
        System.out.println("\n----------- GESTION MENUS -----------");
        System.out.println("[INFO] Fonctionnalite en cours de developpement");
        System.out.println("Les menus seront geres dans une prochaine version");
        System.out.println("Menu = Burger + Boisson + Frites");
    }
    
    private static void gererComplements() {
        System.out.println("\n----------- GESTION COMPLEMENTS -----------");
        System.out.println("[INFO] Fonctionnalite en cours de developpement");
        System.out.println("Complements = Frites ou Boissons");
        System.out.println("Chaque complement a un nom, un prix et une image");
    }
    
    private static int lireEntier() {
        while (true) {
            try {
                String input = scanner.nextLine();
                return Integer.parseInt(input);
            } catch (NumberFormatException e) {
                System.out.print("[X] Entier invalide. Reessayez : ");
            }
        }
    }
    
    private static double lireDouble() {
        while (true) {
            try {
                String input = scanner.nextLine();
                return Double.parseDouble(input);
            } catch (NumberFormatException e) {
                System.out.print("[X] Nombre invalide. Reessayez : ");
            }
        }
    }
}
