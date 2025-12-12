package com.brasibturger.models;

import lombok.*;
import jakarta.persistence.*;
import java.math.BigDecimal;

@Entity
@Table(name = "ligne_commande_complement")
@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class LigneCommandeComplement {
    
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long idLigneComplement;
    
    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "id_ligne", nullable = false)
    private LigneCommande ligneCommande;
    
    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "id_complement", nullable = false)
    private Complement complement;
    
    @Column(nullable = false)
    private Integer quantite = 1;
    
    @Column(nullable = false)
    private BigDecimal prixUnitaire;
    
    @Column(nullable = false)
    private BigDecimal sousTotal;
}
