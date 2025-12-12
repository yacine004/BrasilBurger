package com.brasibturger.controllers;

import com.brasibturger.dtos.GestionnaireDTO;
import com.brasibturger.services.GestionnaireService;
import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@RequestMapping("/api/gestionnaires")
@RequiredArgsConstructor
public class GestionnaireController {
    
    private final GestionnaireService gestionnaireService;
    
    @GetMapping
    public ResponseEntity<List<GestionnaireDTO>> getAllGestionnaires() {
        return ResponseEntity.ok(gestionnaireService.getAllGestionnaires());
    }
    
    @GetMapping("/actifs")
    public ResponseEntity<List<GestionnaireDTO>> getActiveGestionnaires() {
        return ResponseEntity.ok(gestionnaireService.getActiveGestionnaires());
    }
    
    @GetMapping("/{id}")
    public ResponseEntity<GestionnaireDTO> getGestionnaireById(@PathVariable Long id) {
        return ResponseEntity.ok(gestionnaireService.getGestionnaireById(id));
    }
    
    @GetMapping("/email/{email}")
    public ResponseEntity<GestionnaireDTO> getGestionnaireByEmail(@PathVariable String email) {
        return ResponseEntity.ok(gestionnaireService.getGestionnaireByEmail(email));
    }
    
    @PostMapping
    public ResponseEntity<GestionnaireDTO> createGestionnaire(@Valid @RequestBody GestionnaireDTO gestionnaireDTO) {
        return ResponseEntity.status(HttpStatus.CREATED).body(gestionnaireService.createGestionnaire(gestionnaireDTO));
    }
    
    @PutMapping("/{id}")
    public ResponseEntity<GestionnaireDTO> updateGestionnaire(
            @PathVariable Long id,
            @Valid @RequestBody GestionnaireDTO gestionnaireDTO) {
        return ResponseEntity.ok(gestionnaireService.updateGestionnaire(id, gestionnaireDTO));
    }
    
    @DeleteMapping("/{id}")
    public ResponseEntity<Void> deleteGestionnaire(@PathVariable Long id) {
        gestionnaireService.deleteGestionnaire(id);
        return ResponseEntity.noContent().build();
    }
}
