package com.brasilburger.model;

public class Burger {
    private Integer id;
    private String nom;
    private String description;
    private Double prix;
    private String image;
    private Boolean isAvailable;
    
    // Constructeur vide
    public Burger() {}
    
    // Constructeur avec tous les champs
    public Burger(Integer id, String nom, String description, Double prix, String image, Boolean isAvailable) {
        this.id = id;
        this.nom = nom;
        this.description = description;
        this.prix = prix;
        this.image = image;
        this.isAvailable = isAvailable;
    }
    
    // Constructeur sans ID (pour creation)
    public Burger(String nom, String description, Double prix, String image) {
        this.nom = nom;
        this.description = description;
        this.prix = prix;
        this.image = image;
        this.isAvailable = true;
    }
    
    // Getters et Setters
    public Integer getId() { return id; }
    public void setId(Integer id) { this.id = id; }
    
    public String getNom() { return nom; }
    public void setNom(String nom) { this.nom = nom; }
    
    public String getDescription() { return description; }
    public void setDescription(String description) { this.description = description; }
    
    public Double getPrix() { return prix; }
    public void setPrix(Double prix) { this.prix = prix; }
    
    public String getImage() { return image; }
    public void setImage(String image) { this.image = image; }
    
    public Boolean getIsAvailable() { return isAvailable; }
    public void setIsAvailable(Boolean isAvailable) { this.isAvailable = isAvailable; }
    
    @Override
    public String toString() {
        return String.format("ID: %d | %s | %.2f FCFA | %s | Image: %s", 
            id, nom, prix, isAvailable ? "Disponible" : "Indisponible", 
            image != null ? image.substring(0, Math.min(50, image.length())) + "..." : "Aucune");
    }
}
