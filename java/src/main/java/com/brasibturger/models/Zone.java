package com.brasibturger.models;

import lombok.*;
import jakarta.persistence.*;
import java.time.LocalDateTime;
import java.util.HashSet;
import java.util.Set;

@Entity
@Table(name = "zone")
@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class Zone {
    
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long idZone;
    
    @Column(nullable = false, length = 150)
    private String nomZone;
    
    @Column(nullable = false)
    private java.math.BigDecimal prixLivraison;
    
    @Column(columnDefinition = "TEXT")
    private String description;
    
    @Builder.Default
    @Column(nullable = false, updatable = false)
    private LocalDateTime dateCreation = LocalDateTime.now();
    
    @Builder.Default
    @Column(nullable = false)
    private LocalDateTime dateModification = LocalDateTime.now();
    
    @Builder.Default
    @OneToMany(mappedBy = "zone", cascade = CascadeType.ALL, fetch = FetchType.LAZY)
    private Set<ZoneQuartier> quartiers = new HashSet<>();
    
    @Builder.Default
    @OneToMany(mappedBy = "zone", cascade = CascadeType.ALL, fetch = FetchType.LAZY)
    private Set<Livreur> livreurs = new HashSet<>();
    
    @Builder.Default
    @OneToMany(mappedBy = "zone", cascade = CascadeType.ALL, fetch = FetchType.LAZY)
    private Set<Commande> commandes = new HashSet<>();
}
