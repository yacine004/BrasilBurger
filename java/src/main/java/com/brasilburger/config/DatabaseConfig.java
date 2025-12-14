package com.brasilburger.config;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class DatabaseConfig {
    
    private static final String URL = "jdbc:postgresql://ep-empty-fire-adg2yddb.c-2.us-east-1.aws.neon.tech/neondb?sslmode=require";
    private static final String USER = "neondb_owner";
    private static final String PASSWORD = "npg_Zwmhr46vDLKy";
    
    private static Connection connection;
    
    public static Connection getConnection() throws SQLException {
        if (connection == null || connection.isClosed()) {
            try {
                Class.forName("org.postgresql.Driver");
                connection = DriverManager.getConnection(URL, USER, PASSWORD);
                System.out.println("[OK] Connexion a la base de donnees etablie.");
            } catch (ClassNotFoundException e) {
                throw new SQLException("Driver PostgreSQL non trouve", e);
            }
        }
        return connection;
    }
    
    public static void closeConnection() {
        if (connection != null) {
            try {
                connection.close();
                System.out.println("[OK] Connexion fermee.");
            } catch (SQLException e) {
                System.err.println("[X] Erreur lors de la fermeture : " + e.getMessage());
            }
        }
    }
}
