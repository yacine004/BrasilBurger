package com.brasibturger.models;

import lombok.*;
import jakarta.persistence.*;
import java.time.LocalDateTime;
import java.util.HashSet;
import java.util.Set;

@Entity
@Table(name = "client")
@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class Client {
    
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long idClient;
    
    @Column(nullable = false, length = 100)
    private String nom;
    
    @Column(nullable = false, length = 100)
    private String prenom;
    
    @Column(nullable = false, unique = true, length = 255)
    private String email;
    
    @Column(nullable = false, length = 20)
    private String telephone;
    
    @Column(nullable = false)
    private String motDePasse;
    
    @Column(nullable = false)
    private LocalDateTime dateInscription;
    
    @Column(length = 500)
    private String adresse;
    
    @Column(nullable = false)
    @Enumerated(EnumType.STRING)
    @Builder.Default
    private Statut statut = Statut.ACTIF;
    
    @Column(nullable = false, updatable = false)
    @Builder.Default
    private LocalDateTime dateCreation = LocalDateTime.now();
    
    @Column(nullable = false)
    @Builder.Default
    private LocalDateTime dateModification = LocalDateTime.now();
    
    @OneToMany(mappedBy = "client", cascade = CascadeType.ALL, fetch = FetchType.LAZY)
    @Builder.Default
    private Set<Commande> commandes = new HashSet<>();
    
    public enum Statut {
        ACTIF, INACTIF
    }
}
