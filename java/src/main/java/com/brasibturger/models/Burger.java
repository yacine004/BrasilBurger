package com.brasibturger.models;

import lombok.*;
import jakarta.persistence.*;
import java.math.BigDecimal;
import java.time.LocalDateTime;
import java.util.HashSet;
import java.util.Set;

@Entity
@Table(name = "burger")
@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class Burger {
    
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long idBurger;
    
    @Column(nullable = false, length = 150)
    private String nom;
    
    @Column(columnDefinition = "TEXT")
    private String description;
    
    @Column(nullable = false)
    private BigDecimal prix;
    
    @Column(length = 500)
    private String image;
    
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
    
    @OneToMany(mappedBy = "burger", cascade = CascadeType.ALL, fetch = FetchType.LAZY)
    @Builder.Default
    private Set<LigneCommande> lignesCommande = new HashSet<>();
    
    public enum Statut {
        ACTIF, ARCHIVE
    }
}
