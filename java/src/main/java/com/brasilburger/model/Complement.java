package com.brasilburger.model;

public class Complement {
    private Integer id;
    private String name;
    private String description;
    private Double price;
    private String type;
    private String imageUrl;
    private Boolean isAvailable;
    
    public Complement() {}
    
    public Complement(Integer id, String name, String description, Double price, String type, String imageUrl, Boolean isAvailable) {
        this.id = id;
        this.name = name;
        this.description = description;
        this.price = price;
        this.type = type;
        this.imageUrl = imageUrl;
        this.isAvailable = isAvailable;
    }
    
    public Complement(String name, String description, Double price, String type) {
        this.name = name;
        this.description = description;
        this.price = price;
        this.type = type;
        this.isAvailable = true;
    }
    
    public Integer getId() { return id; }
    public void setId(Integer id) { this.id = id; }
    
    public String getName() { return name; }
    public void setName(String name) { this.name = name; }
    
    public String getDescription() { return description; }
    public void setDescription(String description) { this.description = description; }
    
    public Double getPrice() { return price; }
    public void setPrice(Double price) { this.price = price; }
    
    public String getType() { return type; }
    public void setType(String type) { this.type = type; }
    
    public String getImageUrl() { return imageUrl; }
    public void setImageUrl(String imageUrl) { this.imageUrl = imageUrl; }
    
    public Boolean getIsAvailable() { return isAvailable; }
    public void setIsAvailable(Boolean isAvailable) { this.isAvailable = isAvailable; }
    
    @Override
    public String toString() {
        return String.format("ID: %d | %s (%s) | %.2f FCFA | %s", 
            id, name, type, price, isAvailable ? "Disponible" : "Indisponible");
    }
}
