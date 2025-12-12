package com.brasibturger.models;

import lombok.*;
import jakarta.persistence.*;
import java.math.BigDecimal;
import java.time.LocalDateTime;

@Entity
@Table(name = "paiement")
@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class Paiement {
    
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long idPaiement;
    
    @OneToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "id_commande", nullable = false, unique = true)
    private Commande commande;
    
    @Column(nullable = false)
    private BigDecimal montant;
    
    @Column(nullable = false, length = 20)
    @Enumerated(EnumType.STRING)
    private Methode methode;
    
    @Column(nullable = false, length = 20)
    @Enumerated(EnumType.STRING)
    private Statut statut = Statut.REUSSI;
    
    @Column(length = 255)
    private String referencePaiement;
    
    @Column(nullable = false, updatable = false)
    private LocalDateTime datePaiement = LocalDateTime.now();
    
    @Column(nullable = false)
    private LocalDateTime dateModification = LocalDateTime.now();
    
    public enum Methode {
        WAVE, OM
    }
    
    public enum Statut {
        REUSSI, ECHOUE
    }
}
