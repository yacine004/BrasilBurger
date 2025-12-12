package com.brasibturger.models;

import lombok.*;
import jakarta.persistence.*;

@Entity
@Table(name = "zone_quartier")
@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class ZoneQuartier {
    
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long idZoneQuartier;
    
    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "id_zone", nullable = false)
    private Zone zone;
    
    @Column(nullable = false, length = 150)
    private String quartier;
}
