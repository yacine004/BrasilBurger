package com.brasilburger.dao;

import com.brasilburger.config.DatabaseConfig;
import com.brasilburger.model.Burger;

import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class BurgerDAO {
    
    public void create(Burger burger) throws SQLException {
        String sql = "INSERT INTO burgers (name, description, base_price, image_url, is_available) VALUES (?, ?, ?, ?, ?)";
        
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS)) {
            
            stmt.setString(1, burger.getNom());
            stmt.setString(2, burger.getDescription());
            stmt.setDouble(3, burger.getPrix());
            stmt.setString(4, burger.getImage());
            stmt.setBoolean(5, burger.getIsAvailable());
            
            int affectedRows = stmt.executeUpdate();
            
            if (affectedRows > 0) {
                try (ResultSet rs = stmt.getGeneratedKeys()) {
                    if (rs.next()) {
                        burger.setId(rs.getInt(1));
                        System.out.println("[OK] Burger cree avec ID: " + burger.getId());
                    }
                }
            }
        }
    }
    
    public List<Burger> findAll() throws SQLException {
        List<Burger> burgers = new ArrayList<>();
        String sql = "SELECT * FROM burgers ORDER BY id";
        
        try (Connection conn = DatabaseConfig.getConnection();
             Statement stmt = conn.createStatement();
             ResultSet rs = stmt.executeQuery(sql)) {
            
            while (rs.next()) {
                Burger burger = new Burger(
                    rs.getInt("id"),
                    rs.getString("name"),
                    rs.getString("description"),
                    rs.getDouble("base_price"),
                    rs.getString("image_url"),
                    rs.getBoolean("is_available")
                );
                burgers.add(burger);
            }
        }
        
        return burgers;
    }
    
    public Burger findById(int id) throws SQLException {
        String sql = "SELECT * FROM burgers WHERE id = ?";
        
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setInt(1, id);
            
            try (ResultSet rs = stmt.executeQuery()) {
                if (rs.next()) {
                    return new Burger(
                        rs.getInt("id"),
                        rs.getString("name"),
                        rs.getString("description"),
                        rs.getDouble("base_price"),
                        rs.getString("image_url"),
                        rs.getBoolean("is_available")
                    );
                }
            }
        }
        
        return null;
    }
    
    public void update(Burger burger) throws SQLException {
        String sql = "UPDATE burgers SET name = ?, description = ?, base_price = ?, image_url = ?, is_available = ? WHERE id = ?";
        
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setString(1, burger.getNom());
            stmt.setString(2, burger.getDescription());
            stmt.setDouble(3, burger.getPrix());
            stmt.setString(4, burger.getImage());
            stmt.setBoolean(5, burger.getIsAvailable());
            stmt.setInt(6, burger.getId());
            
            int affectedRows = stmt.executeUpdate();
            if (affectedRows > 0) {
                System.out.println("[OK] Burger mis a jour");
            }
        }
    }
    
    public void delete(int id) throws SQLException {
        String sql = "DELETE FROM burgers WHERE id = ?";
        
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setInt(1, id);
            
            int affectedRows = stmt.executeUpdate();
            if (affectedRows > 0) {
                System.out.println("[OK] Burger supprime");
            }
        }
    }
}
