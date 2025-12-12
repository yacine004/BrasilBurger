package com.brasibturger.controllers;

import com.brasibturger.dtos.PaiementDTO;
import com.brasibturger.services.PaiementService;
import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@RequestMapping("/api/paiements")
@RequiredArgsConstructor
public class PaiementController {
    
    private final PaiementService paiementService;
    
    @GetMapping
    public ResponseEntity<List<PaiementDTO>> getAllPaiements() {
        return ResponseEntity.ok(paiementService.getAllPaiements());
    }
    
    @GetMapping("/{id}")
    public ResponseEntity<PaiementDTO> getPaiementById(@PathVariable Long id) {
        return ResponseEntity.ok(paiementService.getPaiementById(id));
    }
    
    @GetMapping("/commande/{commandeId}")
    public ResponseEntity<PaiementDTO> getPaiementByCommande(@PathVariable Long commandeId) {
        return ResponseEntity.ok(paiementService.getPaiementByCommande(commandeId));
    }
    
    @PostMapping
    public ResponseEntity<PaiementDTO> createPaiement(@Valid @RequestBody PaiementDTO paiementDTO) {
        return ResponseEntity.status(HttpStatus.CREATED).body(paiementService.createPaiement(paiementDTO));
    }
    
    @PutMapping("/{id}")
    public ResponseEntity<PaiementDTO> updatePaiement(
            @PathVariable Long id,
            @Valid @RequestBody PaiementDTO paiementDTO) {
        return ResponseEntity.ok(paiementService.updatePaiement(id, paiementDTO));
    }
    
    @DeleteMapping("/{id}")
    public ResponseEntity<Void> deletePaiement(@PathVariable Long id) {
        paiementService.deletePaiement(id);
        return ResponseEntity.noContent().build();
    }
}
