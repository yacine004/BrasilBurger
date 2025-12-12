package com.brasibturger.models;

import lombok.*;
import jakarta.persistence.*;
import java.time.LocalDate;
import java.time.LocalDateTime;

@Entity
@Table(name = "gestionnaire")
@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class Gestionnaire {
    
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long idGestionnaire;
    
    @Column(nullable = false, length = 100)
    private String nom;
    
    @Column(nullable = false, length = 100)
    private String prenom;
    
    @Column(nullable = false, unique = true, length = 255)
    private String email;
    
    @Column(nullable = false)
    private String motDePasse;
    
    @Column(length = 20)
    private String telephone;
    
    @Column(nullable = false)
    private LocalDate dateEmbauche;
    
    @Column(nullable = false)
    @Enumerated(EnumType.STRING)
    private Statut statut = Statut.ACTIF;
    
    @Column(nullable = false, updatable = false)
    private LocalDateTime dateCreation = LocalDateTime.now();
    
    @Column(nullable = false)
    private LocalDateTime dateModification = LocalDateTime.now();
    
    public enum Statut {
        ACTIF, INACTIF
    }
}
